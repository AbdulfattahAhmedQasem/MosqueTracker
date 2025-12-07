<?php

namespace App\Exceptions;

use Exception;

class OptimisticLockException extends Exception
{
    /**
     * Create a new optimistic lock exception instance.
     *
     * @param string $modelName
     * @param int $expectedVersion
     * @param int $actualVersion
     */
    public function __construct(
        protected string $modelName,
        protected int $expectedVersion,
        protected int $actualVersion
    ) {
        $message = sprintf(
            'تضارب في البيانات: تم تحديث %s من قبل مستخدم آخر. النسخة المتوقعة: %d، النسخة الحالية: %d',
            $this->getArabicModelName($modelName),
            $expectedVersion,
            $actualVersion
        );

        parent::__construct($message);
    }

    /**
     * Get the Arabic name for the model.
     *
     * @param string $modelName
     * @return string
     */
    protected function getArabicModelName(string $modelName): string
    {
        $names = [
            'Member' => 'العضو',
            'TransferHistory' => 'سجل النقل',
            'Document' => 'المستند',
            'Housing' => 'المسكن',
        ];

        return $names[$modelName] ?? $modelName;
    }

    /**
     * Get the model name.
     *
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * Get the expected version.
     *
     * @return int
     */
    public function getExpectedVersion(): int
    {
        return $this->expectedVersion;
    }

    /**
     * Get the actual version.
     *
     * @return int
     */
    public function getActualVersion(): int
    {
        return $this->actualVersion;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => 'optimistic_lock_conflict',
                'model' => $this->modelName,
                'expected_version' => $this->expectedVersion,
                'actual_version' => $this->actualVersion,
            ], 409);
        }

        return back()
            ->withInput()
            ->with('error', $this->getMessage())
            ->with('optimistic_lock_conflict', true);
    }
}
