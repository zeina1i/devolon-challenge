<?php


namespace App\Http\Controllers;


use App\Exceptions\ClosedCartException;
use App\Exceptions\ExistsException;
use App\Exceptions\NotFoundException;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::error($e->getMessage());
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
            'product_id' => 'required|integer',
            'cart_id' => 'required|integer'
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
            ], Response::HTTP_NOT_FOUND);
        } catch (ExistsException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (ClosedCartException $e) {
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
            'product_id' => 'required|integer',
            'cart_id' => 'required|integer'
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
            ], Response::HTTP_NOT_FOUND);
        } catch (ClosedCartException $e) {
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
            'message' => 'item removed successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }

    public function changeQuantity(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'product_id' => 'required|integer',
            'cart_id' => 'required|integer',
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
            $cartDTO = $this->cartService->changeQuantity($data['cart_id'], $data['product_id'], $data['quantity']);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (ClosedCartException $e) {
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
            'message' => 'quantity changed successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }

    public function close(Request $request)
    {
        $data = $request->json()->all();
        $rules = [
            'cart_id' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'message' => $this->getValidationErrorsString($validator->errors()->all()),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $cartDTO = $this->cartService->close($data['cart_id']);
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
            'message' => 'quantity closed successfully',
            'data' => $cartDTO->jsonSerialize(),
        ]);
    }
}