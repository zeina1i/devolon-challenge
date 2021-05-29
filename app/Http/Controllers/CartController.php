<?php


namespace App\Http\Controllers;


use App\Exceptions\ExistsException;
use App\Exceptions\NotFoundException;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    private $cartService;

    public function __construct(
        CartService $cartService
    )
    {
        $this->cartService = $cartService;
    }

    public function create()
    {
        try {
            $cartDTO = $this->cartService->create();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'cart created successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }

    public function addItem(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required',
            'cart_id' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $cartDTO = $this->cartService->addItem($data['cart_id'], $data['product_id']);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (ExistsException $e) {
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

        return response()->json(
            [
                'status' => true,
                'message' => 'item added successfully',
                'data' => $cartDTO->jsonSerialize(),
            ]);
    }

    public function removeItem(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required',
            'cart_id' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $cartDTO = $this->cartService->removeItem($data['cart_id'], $data['product_id']);
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
            'message' => 'item removed successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }

    public function changeQuantity(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required',
            'cart_id' => 'required',
            'quantity' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $cartDTO = $this->cartService->changeQuantity($data['cart_id'], $data['product_id'], $data['quantity']);
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
            'message' => 'quantity changed successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }
}