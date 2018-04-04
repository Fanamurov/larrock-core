<?php

namespace Larrock\Core\Tests\Traits;

use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider;
use Larrock\ComponentBlocks\Models\Blocks;
use Larrock\Core\Component;
use Larrock\Core\LarrockCoreServiceProvider;
use Larrock\Core\Tests\DatabaseTest\CreateBlocksDatabase;
use Larrock\Core\Tests\DatabaseTest\CreateMediaDatabase;
use Larrock\Core\Traits\GetFilesAndImages;
use Orchestra\Testbench\TestCase;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\MediaLibrary\Models\Media;

class GetFilesAndImagesTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('medialibrary.disk_name', 'public');
        $app['config']->set('medialibrary.media_model', \Spatie\MediaLibrary\Models\Media::class);
        //$app['config']->set('medialibrary.path_generator', \Larrock\Core\Helpers\CustomPathGenerator::class);
        $app['config']->set('filesystems.disks.media.driver', 'local');
        $app['config']->set('filesystems.disks.media.root', public_path());
    }

    protected function setUp()
    {
        parent::setUp();

        $seed = new CreateBlocksDatabase();
        $seed->setUpBlocksDatabase();

        $seed = new CreateMediaDatabase();
        $seed->setUpMediaDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LarrockCoreServiceProvider::class,
            LarrockComponentBlocksServiceProvider::class,
            MediaLibraryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LarrockBlocks' => 'Larrock\ComponentBlocks\Facades\LarrockBlocks',
            'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs',
            'Image' => 'Intervention\Image\Facades\Image'
        ];
    }

    protected function addMediaItem()
    {
        \DB::connection()->table('media')->insert([
            'model_id' => 1,
            'model_type' => 'Larrock\ComponentBlocks\Models\Blocks',
            'collection_name' => 'images',
            'name' => 'test',
            'file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 'media',
            'size' => 1000,
            'manipulations' => '[]',
            'responsive_images' => '[]',
            'custom_properties' => '{"alt": "photo", "gallery": "gelievye-shary"}',
            'order_column' => 1
        ]);
    }

    public function testRegisterMediaConversions()
    {
        $mock = new class { use GetFilesAndImages; };
        /** @var Component config */
        $mock->config = new BlocksComponent();
        $mock->config->addCustomMediaConversions(['150x150']);
        $media = new Media();
        $mock->registerMediaConversions($media);
        $this->assertCount(3, $mock->mediaConversions);
        $this->assertEquals('110x110', $mock->mediaConversions[0]->getName());
        $this->assertEquals('140x140', $mock->mediaConversions[1]->getName());
        $this->assertEquals('150x150', $mock->mediaConversions[2]->getName());
    }

    public function testGetFirstImage()
    {
        $this->addMediaItem();

        $model = Blocks::find(1);
        $test = $model->getFirstImage;
        $this->assertInstanceOf(Media::class, $test);
        $this->assertEquals(2, $test->id);
    }

    public function testGetFirstImageAttribute()
    {
        $model = Blocks::find(1);
        $this->assertEquals('/_assets/_front/_images/empty_big.png', $model->first_image);
    }

    public function testGetFirstImage110Attribute()
    {
        $model = Blocks::find(1);
        $this->assertEquals('/_assets/_front/_images/empty_big.png', $model->first_image110);
    }

    public function testGetFirstImage140Attribute()
    {
        $model = Blocks::find(1);
        $this->assertEquals('/_assets/_front/_images/empty_big.png', $model->first_image140);
    }
}