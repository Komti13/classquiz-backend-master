<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reward;
use App\User;
use App\UserReward;
use App\Http\Resources\Reward as RewardResource;
use App\Http\Resources\UserReward as UserRewardResource;

/**
 * @resource Rewards
 *
 */
class RewardController extends Controller
{
    /**
     * Display a listing of the reward.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RewardResource::collection(Reward::all());
    }

    /**
     * Store a newly created reward in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'hours' => 'required|integer|max:255',
            'minutes' => 'required|integer|max:255',
            'seconds' => 'required|numeric|max:255',
            'int_condition' => 'required|integer|max:255',
            'reward_type' => 'required|string|max:255',
            'reward_value' => 'required|integer|max:255',
        ]);
        $reward = new Reward;
        $reward->name = request('name');
        $reward->description = request('description');
        $reward->hours = request('hours');
        $reward->minutes = request('minutes');
        $reward->seconds = request('seconds');
        $reward->int_condition = request('int_condition');
        $reward->reward_type = request('reward_type');
        $reward->reward_value = request('reward_value');

        $reward->save();

        return new RewardResource($reward);
    }

    /**
     * Display the specified reward.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new RewardResource(Reward::findOrFail($id));
    }

    /**
     * Update the specified reward in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);
        request()->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'hours' => 'required|integer|max:255',
            'minutes' => 'required|integer|max:255',
            'seconds' => 'required|numeric|max:255',
            'int_condition' => 'required|integer|max:255',
            'reward_type' => 'required|string|max:255',
            'reward_value' => 'required|integer|max:255',
        ]);
        $reward->name = request('name');
        $reward->description = request('description');
        $reward->hours = request('hours');
        $reward->minutes = request('minutes');
        $reward->seconds = request('seconds');
        $reward->int_condition = request('int_condition');
        $reward->reward_type = request('reward_type');
        $reward->reward_value = request('reward_value');

        return new RewardResource($reward);

    }

    /**
     * Remove the specified reward from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reward = Reward::destroy($id);
        return response()->json([
            'message' => 'Reward deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of the reward for the specified user.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function getUserReward($userId)
    {
        $user = User::findOrFail($userId);
        $rewards = UserReward::where('user_id', $user->id)->get();

        return UserRewardResource::collection($rewards);
    }

    /**
     * Add reward to user
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addRewardToUser()
    {
        request()->validate([
            'user_id' => 'required|exists:users,id',
            'reward_id' => 'required|exists:rewards,id',
            'is_token' => 'required|boolean',
            'in_list' => 'required|boolean',
        ]);

        $user = User::findOrFail(request('user_id'));
        $user->rewards()->sync([request('reward_id') => [
            'is_token' => request('is_token'),
            'in_list' => request('in_list')
        ]], false);

        return response()->json([
            'message' => 'Reward added to user successfully'
        ], 200);
    }

    /**
     * Update user reward
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserReward($userId, $rewardId)
    {
        request()->validate([
            'is_token' => 'required|boolean',
            'in_list' => 'required|boolean',
        ]);

        $user = User::findOrFail($userId);
        $user->rewards()->sync([$rewardId => [
            'is_token' => request('is_token'),
            'in_list' => request('in_list')
        ]], false);

        return response()->json([
            'message' => 'User reward updated successfully'
        ], 200);
    }


    /**
     * Remove reward from user
     * @authenticated
     * @param  [int] userId
     * @param  [int] rewardId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeRewardFromUser($userId, $rewardId)
    {
        $user = User::findOrFail($userId);
        $user->rewards()->detach($rewardId);
        return response()->json([
            'message' => 'Reward deleted from user successfully'
        ], 200);
    }
}
