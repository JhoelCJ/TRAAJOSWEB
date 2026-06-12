import { Injectable } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { PrismaService } from "../prisma/prisma.service";

type IngredientFilters = {
  name?: string;
  category?: string;
};

@Injectable()
export class IngredientsService {
  constructor(private readonly prisma: PrismaService) {}

  async findAll(filters: IngredientFilters) {
    const where: Prisma.IngredientWhereInput = {};

    if (filters.name) {
      where.name = { contains: filters.name, mode: "insensitive" };
    }

    if (filters.category) {
      where.category = { equals: filters.category, mode: "insensitive" };
    }

    return this.prisma.ingredient.findMany({
      where,
      orderBy: { name: "asc" },
    });
  }
}
