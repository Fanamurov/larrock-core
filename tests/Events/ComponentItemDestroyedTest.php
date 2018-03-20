<?php

namespace Larrock\Core\Tests\Events;

use Illuminate\Http\Request;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Events\ComponentItemDestroyed;
use Orchestra\Testbench\TestCase;

class ComponentItemDestroyedTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarrockComponentBlocksServiceProvider::class
        ];
    }

    public function test()
    {
        $BlocksComponent = new BlocksComponent();
        $data = new Blocks();
        $request = new Request();
        $ComponentItemDestroyed = new ComponentItemDestroyed($BlocksComponent, $data, $request);

        $this->assertInstanceOf(BlocksComponent::class, $ComponentItemDestroyed->component);
        $this->assertInstanceOf(Blocks::class, $ComponentItemDestroyed->model);
        $this->assertInstanceOf(Request::class, $ComponentItemDestroyed->request);

    }
}