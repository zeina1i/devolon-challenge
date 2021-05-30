<?php


namespace App\Http\Controllers;


use App\Exceptions\ExistsException;
use App\Exceptions\NotFoundException;
use App\Service\DTO\SpecialPriceDTO;
use App\Service\SpecialPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SpecialPriceController extends Controller
{
    private $specialPriceService;
    public function __construct(
        SpecialPriceService $specialPriceService
    )
    {
        $this->specialPriceService = $specialPriceService;
    }

    public function create(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $specialPriceDTO = new SpecialPriceDTO(null, $data['product_id'], $data['quantity'], $data['price']);
            $specialPriceDTO = $this->specialPriceService->add($specialPriceDTO);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (ExistsException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'special price created successfully',
            'data' => $specialPriceDTO->jsonSerialize(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $specialPriceDTO = new SpecialPriceDTO($data['id'], $data['product_id'], $data['quantity'], $data['price']);
            $specialPriceDTO = $this->specialPriceService->update($specialPriceDTO);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'special price updated successfully',
            'data' => $specialPriceDTO->jsonSerialize(),
        ]);
    }

    public function delete(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->specialPriceService->delete($data['product_id'], $data['quantity']);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'special price deleted successfully',
        ]);
    }
}