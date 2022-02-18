<?php
$I = new AcceptanceTester($scenario ?? null);
$I->wantTo('see comments from DB displayed on page');

$I->seeNumRecords(0, "comments");

$randomNumber = rand();

$title = "Title $randomNumber";
$text = "Some text $randomNumber";

$id = $I->haveInDatabase('comments', ['title' => $title, 'text' => $text]);

$I->amOnPage('/comments');
$I->seeLink($title, "/comments/$id");

$I->click($title);
$I->seeCurrentUrlEquals("/comments/$id");

$I->see($title, 'h1');
$I->see($text, 'div');
