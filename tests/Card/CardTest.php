<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 * Covers Card, CardHand, DeckOfCards and CardGraphic
 *
 */
class CardTest extends TestCase
{
    /**
     * Testing if card Hand can be created
     * @return void
     */
    public function testCreateCardHand()
    {
        $hand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $hand);
        $this->assertEmpty($hand->showHand());
    }

    /**
     * Testing if deck can be created
     * @return void
     */
    public function testCreatedeck()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
        $this->assertCount(52, $deck->getCards());
    }
    /**
     * Testing if card can be created
     * @return void
     */
    public function testCreateCard()
    {
        $card = new Card("A", "♥");
        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertEquals("A", $card->rank);
        $this->assertEquals("♥", $card->suit);
        $this->assertEquals("[A♥]", $card->getAsString());
    }
    /**
     * Testing if drawCard is working
     * @return void
     */

    public function testDrawCard()
    {
        $deck = new DeckOfCards();
        $hand = new CardHand();
        $hand->drawCard($deck);
        $this->assertCount(51, $deck->getCards());
        $this->assertNotEmpty($hand->showHand());
    }

    /**
     * Testing if shullfe works
     * @return void
     */
    public function testShuffe()
    {
        $deck = new DeckOfCards();
        $shuffledDeck = new DeckOfCards();
        $shuffledDeck->shuffle();

        $this->assertNotSame($deck->getCards(), $shuffledDeck->getCards());
    }

    /**
     * Testing if getLastDrawnCard works
     * @return void
     */

    public function testGetLastDrawnCard()
    {
        $deck = new DeckOfCards();
        $hand = new CardHand();
        $hand->drawCard($deck);
        $lastDrawn = $hand->getLastDrawnCard();
        $this->assertInstanceOf("\App\Card\Card", $lastDrawn);
        $this->assertEquals($lastDrawn->getAsString(), "[A♠]");
        $this->assertNotEmpty($hand->showHand());
    }
    /**
     * Testing if getLastDrawnCard works if hand is empty
     * @return void
     */

    public function testGetLastDrawnCardEmpty()
    {
        $hand = new CardHand();
        $lastDrawn = $hand->getLastDrawnCard();
        $this->assertNull($lastDrawn);
        $this->assertEmpty($hand->showHand());
    }

    /**
     * Testing if getHandValue works
     * @return void
     */
    public function testGetHandValue()
    {
        $deck = new DeckOfCards();
        $hand = new CardHand();
        $hand->drawCard($deck);
        $hand->drawCard($deck);
        $this->assertEquals(13, $hand->getHandValue());
    }

    /**
     * Testing if Display graphis works
     * @return void
     */
    public function testDisplay()
    {
        $cardGraphic = new CardGraphic("A", "♥");

        $this->assertInstanceOf(CardGraphic::class, $cardGraphic);


        $this->assertSame('🂠', $cardGraphic->display());
    }

    /**
     * Testing if Display works
     * @return void
     */
    public function testDisplayDeck()
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->display(52));
    }

    /**
     * Testing if Display works on empty deck
     * @return void
     */
    public function testDisplayDeckEmpty()
    {
        $deck = new DeckOfCards();

        $this->assertEmpty($deck->display(0));
    }
}
