<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $cards = Card::with('products', 'products.product')
            ->when($request->input('keywords'), fn($query, $keywords) => $query->where('name', 'like', "%{$keywords}%"))
            ->when($request->input('type'), fn($query, $type) => $query->where('type', $type))
            ->where('store_id', $this->store_id)
            ->paginate(getPerPage());

        return success($cards);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $card = Card::findOr($request->input('id'), fn() => new Card(['store_id' => $this->store_id]));

        $card->fill($request->only(['store_id', 'name', 'type', 'price', 'valid_type', 'valid_time', 'remark']));

        $card->save();

        CardProduct::where('card_id', $card->id)->delete();

        $card_products = [];

        $products = $request->input('products', []);
        $gifts = $request->input('gifts', []);

        foreach ($products as $product) {
            $card_products[] = array_merge(
                Arr::only($product, ['product_id', 'number', 'name']),
                ['card_id' => $card->id, 'type' => CardProduct::TYPE_SERVICE]
            );
        }

        foreach ($gifts as $gift) {
            $card_products[] = array_merge(
                Arr::only($gift, ['product_id', 'number', 'name']),
                ['card_id' => $card->id, 'type' => CardProduct::TYPE_GIFT, 'name' => $gift['name']]);
        }

        CardProduct::insert($card_products);

        return success();
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $card = Card::with(['products', 'products.product'])->find($request->input('id'))->toArray();

        foreach ($card['products'] as $product) {
            if ($product['type'] == CardProduct::TYPE_GIFT) {
                $card['gifts'][] = $product;
            }
        }

        return success($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Card::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
