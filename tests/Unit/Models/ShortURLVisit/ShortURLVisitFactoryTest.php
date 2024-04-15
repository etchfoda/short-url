<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURLVisit;

use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use AshAllenDesign\ShortURL\Providers\ShortURLProvider;

class ShortURLVisitFactoryTest extends TestCase
{
    /** @test */
    public function test_that_short_url_visit_model_factory_works_fine(): void
    {
        $model = ShortURLProvider::getShortURLModelInstance();
        $modelVisit = ShortURLProvider::determineShortURLVisitModel();

        $shortURL = $model::factory()->create();

        $shortURLVisit = $modelVisit::factory()->for($shortURL)->create();

        $this->assertDatabaseCount('short_url_visits', 1)
            ->assertDatabaseCount('short_urls', 1)
            ->assertModelExists($shortURLVisit)
            ->assertModelExists($shortURL);

        $this->assertTrue($shortURLVisit->shortURL->is($shortURL));
    }
}
