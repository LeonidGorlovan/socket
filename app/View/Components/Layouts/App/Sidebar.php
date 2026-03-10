<?php

namespace App\View\Components\Layouts\App;

use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public function render(): View|Factory|Htmlable|\Closure|string|\Illuminate\View\View
    {
        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->get()
            ->sort(fn (User $a, User $b): int => strnatcasecmp($a->name, $b->name))
            ->values();

        return view('components.sidebar', ['users' => $users]);
    }
}
