<?php
declare(strict_types=1);

namespace Baris\StoreBodyClass\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;

class AddStoreBodyClass implements ObserverInterface
{
    public function __construct(
        private StoreManagerInterface $storeManager,
        private State $appState
    ) {
    }

    public function execute(Observer $observer): void
    {
        // Skip in admin area
        try {
            if ($this->appState->getAreaCode() === Area::AREA_ADMIN) {
                return;
            }
        } catch (\Exception $e) {
            // If area code cannot be determined, continue (likely frontend)
        }

        $pageConfig = $observer->getData('page_config');
        if (!$pageConfig instanceof PageConfig) {
            return;
        }

        try {
            $store = $this->storeManager->getStore();

            // Add store code class (e.g., "store-fr")
            $storeCode = $store->getCode();
            if ($storeCode) {
                $pageConfig->addBodyClass('store-' . $storeCode);
            }

            // Add normalized store name class (e.g., "store-name-french-store")
            $storeName = $store->getName();
            if ($storeName) {
                $normalizedName = $this->normalizeStoreName($storeName);
                $pageConfig->addBodyClass('store-name-' . $normalizedName);
            }
        } catch (\Exception $e) {
            // Silently fail - don't break the page if something goes wrong
        }
    }

    private function normalizeStoreName(string $storeName): string
    {
        // Convert to lowercase
        $normalized = strtolower($storeName);

        // Replace spaces and special characters with hyphens
        // \p{L} matches any Unicode letter, \p{N} matches any Unicode number
        $normalized = preg_replace('/[^\p{L}\p{N}]+/u', '-', $normalized);

        // Remove leading and trailing hyphens
        $normalized = trim($normalized, '-');

        return $normalized;
    }
}
