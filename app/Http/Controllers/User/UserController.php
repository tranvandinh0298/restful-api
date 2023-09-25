<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->allowedAdminAction();
        
        $users = User::all();

        return $this->showUsers($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['verified'] = User::UNVERIFIED_USER;
        $input['verification_token'] = User::generateVerificationCode();
        $input['admin']  = User::REGULAR_USER;

        $user = User::create($input);

        return $this->showUser($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->showUser($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            $this->allowedAdminAction();

            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }

            $user->admin = $request->admin;
        }

        // isDirty() kiểm tra xem bất cứ thuộc tính nào của user đã bị thay đổi kể từ lúc get user
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $user->save();

        return $this->showUser($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        $user->delete();

        return $this->showUser($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;

        $user->save();

        return $this->showMessage('The account has been verified succesfully');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        });

        return $this->showMessage('The verification email has been resend');
    }
}
