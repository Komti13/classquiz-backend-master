<?php

namespace App\Http\Controllers\Admin;

use App\Token;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class TokenController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $tokens = Token::query()->with('subscription.user');
            return datatables()->eloquent($tokens)->toJson();
        }

        return view('admin.token.index');
    }

    public function generateToken()
    {
        $token = mt_rand(100000000, 999999999);
        if ($this->tokenExists($token)) {
            return $this->generateToken();
        }

        return $token;
    }

    protected function tokenExists($token)
    {
        return Token::where('token', $token)->exists();
    }

    public function create()
    {
        $generatedToken = $this->generateToken();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'STUDENT');
        })
            ->pluck('username', 'id');
        return view('admin.token.create', compact('generatedToken', 'users'));
    }

    public function store()
    {
        request()->validate([
            'value' => 'required|string|max:255',
            'validity_start' => 'required|date',
            'validity_end' => 'required|date',
            'user_id' => 'nullable|integer|exists:users,id',
            'token' => 'required|string|unique:tokens',
            'private' => 'boolean',
        ]);
        $token = new Token;
        $token->value = request('value');
        $token->validity_start = request('validity_start');
        $token->validity_end = request('validity_end');
        $token->user_id = request('user_id');
        $token->token = request('token');
        $token->private = request('private') ? 1 : 0;

        $token->save();


        return redirect()->route('tokens.index')
            ->with('success', 'Token created successfully');
    }

    public function batchStore()
    {
        request()->validate([
            'value' => 'required|string|max:255',
            'validity_start' => 'required|date',
            'validity_end' => 'required|date',
            'number' => 'required|integer'
        ]);

        $tokens = [];
        for ($i = 0; $i < request('number'); $i++) {
            $token = new Token;
            $token->value = request('value');
            $token->validity_start = request('validity_start');
            $token->validity_end = request('validity_end');
            $token->token = $this->generateToken();
            $token->private = 1;

            $token->save();

            $tokens[]= [(string)$token->token, (string)$token->created_at];
        }
        return Excel::create('tokens-' . time(), function ($excel) use ($tokens) {
            $excel->sheet('Tokens', function ($sheet) use ($tokens) {
                $sheet->rows([['Code', 'Created at']]);
                $sheet->rows($tokens);
            });
        })->download('xls');
    }

    public function edit($id)
    {

        $token = Token::findOrFail($id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'STUDENT');
        })
            ->pluck('username', 'id');
        return view('admin.token.edit', compact('token', 'users'));
    }


    public function update($id)
    {
        $token = Token::findOrFail($id);
        request()->validate([
            'value' => 'required|string|max:255',
            'validity_start' => 'required|date',
            'validity_end' => 'required|date',
            'user_id' => 'nullable|integer|exists:users,id',
            'token' => 'required|string|unique:tokens,token,' . $token->id,
            'private' => 'boolean',
        ]);
        $token->value = request('value');
        $token->validity_start = request('validity_start');
        $token->validity_end = request('validity_end');
        $token->user_id = request('user_id');
        $token->private = request('private') ? 1 : 0;

        $token->save();
        return redirect()->route('tokens.index')
            ->with('success', 'Token edited successfully');
    }


    public function destroy($id)
    {
        Token::destroy($id);
        session()->flash('success', 'Token deleted successfully');

        return response()->json(['success' => true]);
    }
}
