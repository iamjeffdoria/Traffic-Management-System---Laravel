Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel is alive']);
});