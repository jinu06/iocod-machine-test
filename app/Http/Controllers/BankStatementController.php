<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Models\BankStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BankStatementController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return view("welcome");
    }

    public function store(Request $request, $id)
    {
        $request->merge([
            'merchant_id' => $id // merge into request for validation
        ]);

        try {
            $request->validate([
                'merchant_id'   => 'required|exists:deals,lead_id',
                'statements'    => 'required|array|max:4',
                'statements.*'  => 'required|file|mimes:pdf,csv',
            ]);

            $files = [];
            foreach ($request->statements as $statement) {
                $uploadedPath = upload_file($statement);
                if (!$uploadedPath) {
                    throw new \Exception("File upload failed");
                }
                $files[] = $uploadedPath;
            }

            $bankStatement = new BankStatement();
            $bankStatement->merchent_id = $id;
            $bankStatement->file = json_encode($files); // store as JSON array
            $bankStatement->save();

            return $this->successResponse($bankStatement);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->validator->errors());
        } catch (\Throwable $th) {
            Log::error("BankStatement Store Error: " . $th->getMessage());
            return $this->errorResponse("Server Error", 500, $th->getMessage());
        }
    }
}
