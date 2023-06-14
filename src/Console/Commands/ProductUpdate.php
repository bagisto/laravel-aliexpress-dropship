<?php

namespace Webkul\Dropship\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Dropship\Repositories\AliExpressProductRepository;

class ProductUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropship:product:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates AliExpress product information (eg. name, description, price and quantity)';

    /**
     * Create a new command instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @return void
     */
    public function __construct(
        protected AliExpressProductRepository $aliExpressProductRepository
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! core()->getConfigData('dropship.settings.auto_updation.quantity')
            || ! core()->getConfigData('dropship.settings.auto_updation.price')) {
            return;
        }

        $aliExpressProducts = $this->aliExpressProductRepository->findWhere(['parent_id' => null], ['id']);

        foreach ($aliExpressProducts as $aliExpressProduct) {
            $this->aliExpressProductRepository->update([], $aliExpressProduct->id);
        }
    }
}
