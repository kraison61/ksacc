<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_returns_only_published_services(): void
    {
        Service::factory()->create(['title' => 'Published Service']);
        Service::factory()->unpublished()->create(['title' => 'Draft Service']);

        $published = Service::query()->published()->pluck('title');

        $this->assertTrue($published->contains('Published Service'));
        $this->assertFalse($published->contains('Draft Service'));
    }

    public function test_ordered_scope_sorts_by_sort_order(): void
    {
        Service::factory()->create(['title' => 'Second', 'sort_order' => 2]);
        Service::factory()->create(['title' => 'First', 'sort_order' => 1]);

        $titles = Service::query()->ordered()->pluck('title')->all();

        $this->assertSame(['First', 'Second'], $titles);
    }
}
