import { Hono } from 'https://deno.land/x/hono@v3.4.1/mod.ts'
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2.39.0'
import { Database } from '../_shared/types/supabase.ts'

const supabase = createClient<Database>(
  Deno.env.get('SUPABASE_URL')!,
  Deno.env.get('SUPABASE_ANON_KEY')!
)

const app = new Hono()

app.basePath('/menu')

app.get('/dishes', async (c) => {
  const category = c.req.query('category')
  const search = c.req.query('search')

  let query = supabase.from('menu_items').select('*')

  if (category) {
    query = (query as any).eq('category', category)
  }

  if (search) {
    query = query.ilike('name', `%${search}%`)
  }

  const { data } = await query

  return c.json(data ?? [], 200)
})

app.get('/dishes/:dishId', async (c) => {
  const dishId = c.req.param('dishId')

  const { data, error } = await supabase
    .from('menu_items')
    .select('*')
    .eq('item_id', dishId)
    .single()

  if (error || !data) {
    return c.json({ error: 'Dish not found' }, 404)
  }

  return c.json(data, 200)
})

app.get('/categories', async (c) => {
  const { data } = await supabase.from('menu_items').select('*')

  const categories = [
    ...new Set(
      (data ?? [])
        .map((item) => (item as any).category)
        .filter((cat): cat is string => typeof cat === 'string')
    ),
  ]

  return c.json(categories, 200)
})

app.get('/ingredients', async (c) => {
  const { data } = await supabase.from('ingredients').select('*')

  return c.json(data ?? [], 200)
})

export default app