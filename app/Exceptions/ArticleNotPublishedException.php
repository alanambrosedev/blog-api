    <?php

    namespace App\Exceptions;

    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class ArticleNotPublishedException extends Exception
    {
        public function __construct(protected string $slug)
        {
            parent::__construct("The article `{$this->slug}` is not yet published.");
        }

        public function report()
        {
            Log::warning('Unauthorized attempt to access unpublished article.', [
                'slug' => $this->slug,
                'ip' => request()->ip,
                'user_id' => auth()->id,
            ]);
        }

        public function render(Request $request)
        {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => $this->getMessage(),
                ], 403);

                return abort(403, $this->getMessage());
            }
        }
    }
