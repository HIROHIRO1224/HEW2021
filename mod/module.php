<?php

/**
 * hメソッド
 * 
 * htmlspecialcharsメソッドを簡略化させた関数
 * @param string $text 無害化させたい文字列
 * @return string 無害化させた文字列
 */
function h(string $text)
{
    # code...
    return htmlspecialchars($text, ENT_QUOTES);
}
