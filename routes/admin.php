<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminTaxonomyController;
use App\Http\Controllers\Admin\AdminTicketTypeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // ── Dashboard ─────────────────────────────────────────────────────
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // ── Posts ─────────────────────────────────────────────────────────
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/',                    [AdminPostController::class, 'index'])->name('index');
            Route::patch('/{post}/status',     [AdminPostController::class, 'updateStatus'])->name('status');
            Route::patch('/{post}/pin',        [AdminPostController::class, 'togglePin'])->name('pin');
            Route::patch('/{post}/lock',       [AdminPostController::class, 'toggleLock'])->name('lock');
            Route::delete('/{post}',           [AdminPostController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/restore',      [AdminPostController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete',[AdminPostController::class, 'forceDelete'])->name('force-delete');
        });

        // ── Comments ──────────────────────────────────────────────────────
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/',              [AdminCommentController::class, 'index'])->name('index');
            Route::delete('/{comment}', [AdminCommentController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore',[AdminCommentController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force',[AdminCommentController::class, 'forceDelete'])->name('force-delete');
        });

        // ── Users ─────────────────────────────────────────────────────────
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',               [AdminUserController::class, 'index'])->name('index');
            Route::patch('/{user}/role',  [AdminUserController::class, 'updateRole'])->name('update-role');
            Route::delete('/{user}',      [AdminUserController::class, 'destroy'])->name('destroy');
        });

        // ── Categories ────────────────────────────────────────────────────
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/',                        [AdminTaxonomyController::class, 'categoriesIndex'])->name('index');
            Route::post('/',                       [AdminTaxonomyController::class, 'categoryStore'])->name('store');
            Route::patch('/{category}',            [AdminTaxonomyController::class, 'categoryUpdate'])->name('update');
            Route::delete('/{category}',           [AdminTaxonomyController::class, 'categoryDestroy'])->name('destroy');
        });

        // ── Tags ──────────────────────────────────────────────────────────
        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/',           [AdminTaxonomyController::class, 'tagsIndex'])->name('index');
            Route::post('/',          [AdminTaxonomyController::class, 'tagStore'])->name('store');
            Route::patch('/{tag}',    [AdminTaxonomyController::class, 'tagUpdate'])->name('update');
            Route::delete('/{tag}',   [AdminTaxonomyController::class, 'tagDestroy'])->name('destroy');
        });

        // ── Post Types ────────────────────────────────────────────────────
        Route::prefix('post-types')->name('post-types.')->group(function () {
            Route::get('/',                [AdminTaxonomyController::class, 'postTypesIndex'])->name('index');
            Route::post('/',               [AdminTaxonomyController::class, 'postTypeStore'])->name('store');
            Route::patch('/{postType}',    [AdminTaxonomyController::class, 'postTypeUpdate'])->name('update');
            Route::delete('/{postType}',   [AdminTaxonomyController::class, 'postTypeDestroy'])->name('destroy');
        });

        // ── Tickets ───────────────────────────────────────────────────────
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/',                  [TicketController::class, 'index'])->name('index');
            Route::get('/{ticket}',          [TicketController::class, 'show'])->name('show');
            Route::patch('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('update-status');
        });

        // ── Ticket Types ──────────────────────────────────────────────────
        Route::prefix('ticket-types')->name('ticket-types.')->group(function () {
            Route::get('/',                      [AdminTicketTypeController::class, 'index'])->name('index');
            Route::post('/',                     [AdminTicketTypeController::class, 'store'])->name('store');
            Route::patch('/{ticketType}',        [AdminTicketTypeController::class, 'update'])->name('update');
            Route::delete('/{ticketType}',       [AdminTicketTypeController::class, 'destroy'])->name('destroy');
        });

    });
