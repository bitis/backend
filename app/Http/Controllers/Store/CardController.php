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
        $cards = Card::with('services', 'services.product')
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

        $services = $request->input('services', []);
        $gifts = $request->input('gifts', []);

        foreach ($services as $service) {
            $card_products[] = array_merge(
                Arr::only($service, ['product_id', 'number', 'name']),
                ['card_id' => $card->id, 'type' => CardProduct::TYPE_SERVICE]
            );
        }

        foreach ($gifts as $gift) {
            $card_products[] = array_merge(
                Arr::only($gift, ['product_id', 'number']),
                ['card_id' => $card->id, 'type' => CardProduct::TYPE_GIFT, 'name' => $service['name']]);
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
        $card = Card::with(['services', 'services.product'])->find($request->input('id'))->toArray();

        foreach ($card['services'] as $product) {
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
