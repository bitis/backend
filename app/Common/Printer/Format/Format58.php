<?php

namespace App\Common\Printer\Format;


class Format58
{
    public const ROW_MAX_CHAR_LEN = 32;
    private const MAX_NAME_CHAR_LEN = 20;
    private const LAST_ROW_MAX_NAME_CHAR_LEN = 16;
    private const MAX_QUANTITY_CHAR_LEN = 8;
    private const MAX_PRICE_CHAR_LEN = 8;

    public function __construct(protected $title = '', protected $items = [], protected $footer = [], protected $qrcode = '', protected $placeholder = ' ')
    {
    }

    protected function calculateLength($string): int
    {
        return (strlen($string) + mb_strlen($string)) / 2;
    }

    public function title(): string
    {
        return "<C><B>$this->title</B><BR></C><BR>";
    }

    public function items(): string
    {
        $contents = "名称" . str_repeat($this->placeholder, 12) .
            "数量" . str_repeat($this->placeholder, 4) .
            "价格" . str_repeat($this->placeholder, 4) .
            "<BR>";

        foreach ($this->items as $item) {
            $contents .= $this->item($item['name'], $item['number'] . '', round($item['price'], 2) . '');
        }

        return $contents;
    }

    private function item(string $name, string $number, string $price): string
    {
        $orderNameEmpty = str_repeat($this->placeholder, self::LAST_ROW_MAX_NAME_CHAR_LEN);

        $result = $name;
        $mod = $this->calculateLength($name) % self::ROW_MAX_CHAR_LEN;
        print("mod=" . $mod . "\n");

        if ($mod <= self::LAST_ROW_MAX_NAME_CHAR_LEN) {
            $result = $result . str_repeat($this->placeholder, self::LAST_ROW_MAX_NAME_CHAR_LEN - $mod);
        } else {
            $result = $result . "<BR>";
            $result = $result . $orderNameEmpty;
        }

        $result = $result . $number . str_repeat($this->placeholder, self::MAX_QUANTITY_CHAR_LEN - strlen($number));
        $result = $result . $price . str_repeat($this->placeholder, self::MAX_PRICE_CHAR_LEN - strlen($price));
        return $result . "<BR>";
    }

    public function footer()
    {

    }

    public function qrcode(): string
    {
        if (empty($this->qrcode)) {
            return '';
        }

        return "<C><QR>$this->qrcode</QR></C>";
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->title() . $this->items() . $this->footer() . $this->qrcode();
    }
}
