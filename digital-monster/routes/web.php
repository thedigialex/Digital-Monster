<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MonsterController;
use App\Http\Controllers\Admin\EggGroupController;
use App\Http\Controllers\Admin\EquipmentController;


Route::middleware('headers')->group(function () {
    //Public
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect('/digigarden');
        } else {
            return view('pages.welcome');
        }
    });

    // Fallback Route - Handles all undefined routes
    Route::fallback(function () {
        if (Auth::check()) {
            return response()->view('pages.404', [], 404);
        } else {
            return redirect('/');
        }
    });

    //Log In Required
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile/policy', [ProfileController::class, 'policy'])->name('profile.policy');
        Route::post('/profile/policy', [ProfileController::class, 'updatePolicy'])->name('profile.policy.update');

        Route::middleware(['policy'])->group(function () {
            Route::get('/digigarden', [DashboardController::class, 'garden'])->name('digigarden');
            Route::get('/digigarden/user', [DashboardController::class, 'gardenUser'])->name('digigarden.user');
            Route::get('/colosseum', [DashboardController::class, 'colosseum'])->name('colosseum');
            Route::post('colosseum/generateBattle', [DashboardController::class, 'generateBattle'])->name('colosseum.generateBattle');
            Route::get('/adventure', [DashboardController::class, 'adventure'])->name('adventure');
            Route::post('adventure/step', [DashboardController::class, 'generateStep'])->name('adventure.step');
            Route::get('/digiconverge', [DashboardController::class, 'converge'])->name('digiconverge');
            Route::post('/digiconverge/create', [DashboardController::class, 'convergeCreate'])->name('converge.create');
            Route::get('/shop', [DashboardController::class, 'shop'])->name('shop');
            Route::post('/shop/buy', [DashboardController::class, 'buyItem'])->name('shop.buy');
            Route::get('/upgradeStation', [DashboardController::class, 'upgradeStation'])->name('upgradeStation');
            Route::post('/upgradeStation/upgrade', [DashboardController::class, 'upgradeEquipment'])->name('upgradeStation.upgrade');
            Route::post('monster/train', [DashboardController::class, 'useTraining'])->name('monster.train');
            Route::post('monster/changeName', [DashboardController::class, 'changeName'])->name('monster.name');

            Route::post('monster/item', [DashboardController::class, 'useItem'])->name('monster.item');
            Route::post('monster/attack', [DashboardController::class, 'changeAttack'])->name('monster.attack');
            Route::post('monster/sleep', [DashboardController::class, 'sleepToggle'])->name('monster.sleep');
            Route::post('monster/evolve', [DashboardController::class, 'evolve'])->name('monster.evolve');

            Route::get('/users', [ProfileController::class, 'index'])->name('users.index');
            Route::post('/users/friend/add', [ProfileController::class, 'addFriend'])->name('users.friend.add');
            Route::post('/users/friend/cancel', [ProfileController::class, 'cancelFriend'])->name('users.friend.cancel');
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            Route::view('/info', 'pages.info')->name('info');
            Route::post('/session/store', [SessionController::class, 'store'])->name('session.store');
            Route::post('/session/clear', [SessionController::class, 'clear'])->name('session.clear');

            //Admin Only
            Route::middleware(['admin'])->group(function () {
                Route::get('/user', [UserController::class, 'profile'])->name('user.profile');

                Route::get('/eggGroups', [EggGroupController::class, 'index'])->name('egg_groups.index');
                Route::get('/eggGroup/edit', [EggGroupController::class, 'edit'])->name('egg_group.edit');
                Route::post('/eggGroup/update', [EggGroupController::class, 'update'])->name('egg_group.update');
                Route::delete('/eggGroup/delete', [EggGroupController::class, 'destroy'])->name('egg_group.destroy');

                Route::get('/monsters', [MonsterController::class, 'index'])->name('monsters.index');
                Route::get('/monster/edit', [MonsterController::class, 'edit'])->name('monster.edit');
                Route::post('/monster/update', [MonsterController::class, 'update'])->name('monster.update');
                Route::delete('/monster/delete', [MonsterController::class, 'destroy'])->name('monster.destroy');
                Route::get('/userMonster/edit', [UserController::class, 'editUserMonster'])->name('user.monster.edit');
                Route::post('/userMonster/update', [UserController::class, 'updateUserMonster'])->name('user.monster.update');
                Route::delete('/userMonster/delete', [UserController::class, 'destroyUserMonster'])->name('user.monster.destroy');

                Route::get('/items', [ItemController::class, 'index'])->name('items.index');
                Route::get('/item/edit', [ItemController::class, 'edit'])->name('item.edit');
                Route::post('/item/update', [ItemController::class, 'update'])->name('item.update');
                Route::delete('/item/delete', [ItemController::class, 'destroy'])->name('item.destroy');
                Route::get('/userItem/edit', [UserController::class, 'editUserItem'])->name('user.item.edit');
                Route::post('/userItem/update', [UserController::class, 'updateUserItem'])->name('user.item.update');
                Route::delete('/userItem/delete', [UserController::class, 'destroyUserItem'])->name('user.item.destroy');

                Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
                Route::get('/equipment/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
                Route::post('/equipment/update', [EquipmentController::class, 'update'])->name('equipment.update');
                Route::delete('/equipment/delete', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
                Route::get('/userEquipment/edit', [UserController::class, 'editUserEquipment'])->name('user.equipment.edit');
                Route::post('/userEquipment/update', [UserController::class, 'updateUserEquipment'])->name('user.equipment.update');
                Route::delete('/userEquipment/delete', [UserController::class, 'destroyUserEquipment'])->name('user.equipment.destroy');

                Route::get('/events', [EventController::class, 'index'])->name('events.index');
                Route::get('/event/edit', [EventController::class, 'edit'])->name('event.edit');
                Route::post('/event/update', [EventController::class, 'update'])->name('event.update');
                Route::delete('/event/delete', [EventController::class, 'destroy'])->name('event.destroy');
            });
        });
    });
});


require __DIR__ . '/auth.php';
