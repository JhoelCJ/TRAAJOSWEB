import { Controller, Get, Query } from "@nestjs/common";
import { IngredientsService } from "./ingredients.service";

@Controller("ingredients")
export class IngredientsController {
  constructor(private readonly ingredientsService: IngredientsService) {}

  @Get()
  findAll(@Query("name") name?: string, @Query("category") category?: string) {
    return this.ingredientsService.findAll({ name, category });
  }
}
