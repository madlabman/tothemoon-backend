<?php

namespace App\Http\Controllers;

use App\Command;
use App\User;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    protected const PER_PAGE = 12;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $commands = Command::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = Command::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/commands/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('command.all')->with([
            'commands' => $commands,
            'pages'    => $pages,
        ]);
    }

    public function delete($id)
    {
        $post = Command::find($id);
        if (!empty($post)) {
            $post->delete();
            \request()->session()->flash('status', 'Команда удалена!');
        }

        return redirect('commands');
    }

    public function validatePostRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
        ], [
            'name.required' => 'Название обязательно',
        ]);
    }

    public function edit($id)
    {
        $command = Command::find($id);
        if ($command == null) return redirect()->back();
        return view('command.show')->with([
            'command' => $command,
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($data = $this->validatePostRequest($request)) {
            $command = Command::find($id);
            if (!empty($command)) {
                $command->update($data);
            }
        }
        $request->session()->flash('status', 'Команда обновлена!');
        return redirect()->back();
    }

    public function new()
    {
        return view('command.show')->with([
            'users' => User::all(),
        ]);
    }

    public function create(Request $request)
    {
        if ($data = $this->validatePostRequest($request)) {
            $admin = User::findOrFail($request->user);
            $command = Command::create($data);
            $command->admin()->associate($admin)->save();
            $request->session()->flash('status', 'Команда создана!');
            return redirect('/commands/edit/' . $command->id);
        }

        return redirect()->back();
    }

}
