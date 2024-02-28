<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Integration\Plugin;

use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\TestFramework\TestCase\AbstractController;

class AddScriptTest extends AbstractController
{
    /**
     * @var string
     */
    private $script;

    protected function setUp(): void
    {
        parent::setUp();
        $this->script = $this->_objectManager->create(ScriptGenerator::class)->generate();
    }

    /**
     * @magentoConfigFixture current_store web/cookiebot/enabled 1
     * @magentoConfigFixture current_store web/cookiebot/id 123-456-789
     */
    public function testGoogleConsentOnHomepage(): void
    {
        $this->dispatch('/');
        self::assertStringContainsString('gtag("set", "ads_data_redaction", true);', $this->getResponse()->getBody());
    }

    /**
     * @magentoConfigFixture current_store web/cookiebot/use_google_consent_mode 1
     * @magentoConfigFixture current_store web/cookiebot/id 123-456-789
     */
    public function testScriptAddedOnHomepage(): void
    {
        $this->dispatch('/');
        self::assertStringContainsString($this->script, $this->getResponse()->getBody());
    }

    /**
     * @magentoDataFixture   Magento/Catalog/_files/product_simple.php
     * @magentoConfigFixture current_store web/cookiebot/enabled 1
     * @magentoConfigFixture current_store web/cookiebot/id 123-456-789
     */
    public function testScriptAddedOnProductPage(): void
    {
        $this->dispatch('/catalog/product/view/id/1');
        self::assertStringContainsString($this->script, $this->getResponse()->getBody());
    }

    /**
     * @magentoDataFixture   Magento/Catalog/_files/category.php
     * @magentoConfigFixture current_store web/cookiebot/enabled 1
     * @magentoConfigFixture current_store web/cookiebot/id 123-456-789
     */
    public function testScriptAddedOnCategoryPage(): void
    {
        $this->dispatch('/catalog/category/view/id/333');
        self::assertStringContainsString($this->script, $this->getResponse()->getBody());
    }

    /**
     * @magentoDataFixture   Magento/Cms/_files/pages.php
     * @magentoConfigFixture current_store web/cookiebot/enabled 1
     * @magentoConfigFixture current_store web/cookiebot/id 123-456-789
     */
    public function testScriptAddedOnCmsPage(): void
    {
        $this->dispatch('/page100');
        self::assertStringContainsString($this->script, $this->getResponse()->getBody());
    }
}
