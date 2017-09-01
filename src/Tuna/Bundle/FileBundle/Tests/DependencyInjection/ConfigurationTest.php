<?php

namespace TunaCMS\Bundle\FileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use TunaCMS\Bundle\FileBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        $this->assertEquals(
            [
                'file_manager' => [
                    'tmp_path' => 'uploads/tmp',
                    'web_root_dir' => '%kernel.root_dir%/../web',
                    'upload_files_path' => 'uploads/files',
                ],
            ],
            $config
        );
    }

    public function testFileManagerAdditionalConfiguration()
    {
        $data = [
            'file_manager' => [
                'tmp_path' => 'tmp/foo',
                'web_root_dir' => './web',
                'upload_files_path' => 'uploads/bar',
            ]
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            $data,
        ]);
        $this->assertEquals($data, $config);
    }

}
