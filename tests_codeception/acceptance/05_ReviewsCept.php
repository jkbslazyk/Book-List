<?php
$I = new AcceptanceTester($scenario ?? null);

$I->wantTo('have reviews page');

$I->amOnPage('/books');
$I->seeCurrentUrlEquals('/login');

$I->fillField('email', 'john.doe@gmail.com');
$I->fillField('password', 'secret');

$I->click('Log in');

$I->click('Create new...');

$bookIsbn = "1234512345123";
$bookTitle = "SomeTitle";
$bookDescriptionIntro = "SomeDescription with";
$bookDescriptionFormatted = "formatting";
$bookDescription = "$bookDescriptionIntro **$bookDescriptionFormatted**";

$I->fillField('isbn', $bookIsbn);
$I->fillField('title', $bookTitle);
$I->fillField('description', $bookDescription);

$I->click('Create');

$I->seeInDatabase('books', [
    'isbn' => $bookIsbn,
    'title' => $bookTitle,
    'description' => $bookDescription
]);

$I->click('Reviews');

$bookId = $I->grabFromDatabase('books', 'id', [
    'isbn' => $bookIsbn
]);

$I->amOnPage('/books/'.$bookId.'/reviews');

$I->see("List of reviews", 'h2');
$I->see('No reviews in database.');

$I->wantTo('add review');

$I->click('Create new...');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/create');

$I->see('Creating a review', 'h2');

$I->click('Create');

$reviewTitle = "ReviewTitle";
$reviewDescription = "ReviewDescription";

$I->dontSeeInDatabase('reviews', [
    'title' => $reviewTitle,
    'description' => $reviewDescription
]);

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/create');

$I->see('The title field is required.', 'li');
$I->see('The description field is required.', 'li');

$I->fillField('title', $reviewTitle);
$I->fillField('description', $reviewDescription);

$I->click('Create');

$I->SeeInDatabase('reviews', [
    'title' => $reviewTitle,
    'description' => $reviewDescription
]);

$reviewId = $I->grabFromDatabase('reviews', 'id', [
    'title' => $reviewTitle
]);

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/'.$reviewId);

$I->see("Viewing a review", 'h2');
$I->see($reviewTitle);
$I->see($reviewDescription);

$I->amOnPage('/books/'.$bookId.'/reviews/');

$I->see("$reviewTitle", 'tr > td');
$I->dontSee("$reviewDescription", 'tr > td');

$I->click('Details');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/'.$reviewId);

$I->click('Edit');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/'.$reviewId.'/edit');
$I->see('Editing a review', 'h2');

$I->seeInField('title', $reviewTitle);
$I->seeInField('description', $reviewDescription);

$I->fillField('description', "");

$I->click('Update');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/'.$reviewId.'/edit');
$I->see('The description field is required.', 'li');

$reviewNewDescription = 'NewReviewDescription';

$I->fillField('description', $reviewNewDescription);
$I->click('Update');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/'.$reviewId);

$I->see($reviewNewDescription);

$I->dontSeeInDatabase('reviews', [
    'title' => $reviewTitle,
    'description' => $reviewDescription
]);

$I->SeeInDatabase('reviews', [
    'title' => $reviewTitle,
    'description' => $reviewNewDescription
]);

$I->click('Delete');

$I->seeCurrentUrlEquals('/books/'.$bookId.'/reviews/');


$I->dontSeeInDatabase('reviews', [
    'title' => $reviewTitle,
    'description' => $reviewNewDescription
]);
