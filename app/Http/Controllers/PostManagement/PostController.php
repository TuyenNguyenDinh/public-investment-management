<?php

namespace App\Http\Controllers\PostManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Imports\PostsImport;
use App\Models\Account;
use App\Models\Category;
use App\Models\OrganizationUnit;
use App\Models\User;
use App\Services\Categories\GetAllCategoriesService;
use App\Services\Organizations\GetAllOrganizationUnitsService;
use App\Services\Posts\CreatePostViewService;
use App\Services\Posts\ExportExcelPostsService;
use App\Services\Posts\GetPostDetailService;
use App\Services\Posts\GetPostsPageViewService;
use App\Services\Posts\StorePostService;
use App\Services\Posts\UpdatePostService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function compact;

class PostController extends Controller
{
    /**
     * Get the posts list
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $results = resolve(GetPostsPageViewService::class)->run();

        return view('content.apps.posts.list', [
            'organizations' => $results['organizations'],
            'totalPosts' => $results['totalPosts'],
            'totalViews' => $results['totalViews'],
            'totalCategories' => $results['totalCategories'],
            'totalPostsPublished' => $results['totalPostsPublished']
        ]);
    }

    /**
     * View page create new a post
     *
     * @return Application|Factory|View
     */
    public function create(): Application|Factory|View
    {
        $results = resolve(CreatePostViewService::class)->run();

        return view('content.apps.posts.store', [
            'organizations' => $results['organizations'],
            'categories' => $results['categories']
        ]);
    }

    /**
     * Action create new a post
     *
     * @param StorePostRequest $request
     * @return RedirectResponse
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $result = resolve(StorePostService::class)->run($request->validated());
        $result ? session()->flash('success', __('create_post_success'))
            : session()->flash('error', __('create_post_error'));

        return match ($request['action']) {
            'save_exit' => redirect()->route('app-posts-index'),
            default => redirect()->back(),
        };
    }

    /**
     * Get the post detail
     *
     * @param string $slug
     * @return Application|Factory|View
     */
    public function show(string $slug): Factory|View|Application
    {
        $post = resolve(GetPostDetailService::class)->run($slug);
        $categories = resolve(GetAllCategoriesService::class)->run();
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();

        return view('content.apps.posts.detail', compact('post', 'categories', 'organizations'));
    }

    /**
     * Update the post detail
     *
     * @param UpdatePostRequest $request
     * @param string $slug
     * @return RedirectResponse
     */
    public function update(UpdatePostRequest $request, string $slug): RedirectResponse
    {
        $result = resolve(UpdatePostService::class)->run($request->validated(), $slug);
        $result ? session()->flash('success', __('update_post_success'))
            : session()->flash('error', __('update_post_fail'));

        return redirect()->back();
    }

    /**
     * Import posts from an Excel file.
     *
     * @param Request $request The HTTP request containing the uploaded Excel file.
     * @return RedirectResponse A redirect response back with a status indicator.
     */
    public function importExcel(Request $request): RedirectResponse
    {
        Excel::import(new PostsImport, $request->file('excel'));

        return redirect()->back()->with('status', true);
    }

    /**
     * Export posts to an Excel file and download it.
     *
     * @return BinaryFileResponse A response that initiates the download of the Excel file.
     */
    public function exportExcel(): BinaryFileResponse
    {
        $result = resolve(ExportExcelPostsService::class)->run();

        return response()->download($result)->deleteFileAfterSend(false);
    }
}
