import { Module } from "@nestjs/common";
import { PrismaModule } from "./prisma/prisma.module";
import { AuthModule } from "./auth/auth.module";
import { CustomersModule } from "./customers/customers.module";
import { MenuModule } from "./menu/menu.module";
import { OrdersModule } from "./orders/orders.module";
import { CartModule } from "./cart/cart.module";
import { InventoryModule } from "./inventory/inventory.module";
import { IngredientsModule } from "./ingredients/ingredients.module";
import { PrrismaModule } from './prrisma/prrisma.module';

@Module({
  imports: [
    PrismaModule,
    AuthModule,
    CustomersModule,
    MenuModule,
    OrdersModule,
    CartModule,
    InventoryModule,
    IngredientsModule,
    PrrismaModule,
  ],
})
export class AppModule {}
