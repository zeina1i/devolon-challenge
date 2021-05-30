<?php


namespace App\Http\Controllers;


use App\Exceptions\EntityNotFoundException;
use App\Exceptions\NotFoundException;
use App\Service\DTO\ProductDTO;
use App\Service\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private $productService;
    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    public function create(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'title' => 'required',
            'unit_price' => 'required|numeric'
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $productDTO = new ProductDTO(null, $data['title'], $data['unit_price']);
            $productDTO = $this->productService->add($productDTO);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'product created successfully',
            'data' => $productDTO->jsonSerialize(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required|integer',
            'title' => 'required',
            'unit_price' => 'required|numeric'
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $productDTO = new ProductDTO($data['product_id'], $data['title'], $data['unit_price']);
            $productDTO = $this->productService->update($productDTO);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'product updated successfully',
            'data' => $productDTO->jsonSerialize(),
        ]);
    }

    public function delete(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->productService->delete(3);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'product removed successfully',
        ]);
    }
}