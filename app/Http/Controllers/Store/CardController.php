<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $cards = Card::when($request->input('keywords'), fn($query, $keywords) => $query->where('name', 'like', "%{$keywords}%"))
            ->where('store_id', $this->store_id)->simplePaginate(getPerPage());

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

        $services = $request->input('services', []);
        $gifts = $request->input('gifts', []);

        foreach ($services as $service) {
            if ($service['number_type'] == CardProduct::NUMBER_TYPE_UNLIMIT) $service['number'] = 999;
            $card_products[] = array_merge($service, ['card_id' => $card->id, 'type' => CardProduct::TYPE_SERVICE]);
        }

        foreach ($gifts as $gift) {
            if ($gift['number_type'] == CardProduct::NUMBER_TYPE_UNLIMIT) $gift['number'] = 999;
            $card_products[] = array_merge($gift, ['card_id' => $card->id, 'type' => CardProduct::TYPE_GIFT]);
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
            $product['type'] == CardProduct::TYPE_SERVICE ? $card['services'][] = $product : $card['gifts'][] = $product;
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
