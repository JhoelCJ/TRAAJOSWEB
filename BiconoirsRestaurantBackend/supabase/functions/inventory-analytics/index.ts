import { Hono } from 'https://deno.land/x/hono@v4.3.11/mod.ts'
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2.42.0'
import { Database } from '../_shared/types/supabase.ts'

const app = new Hono()

app.use('*', async (c, next) => {
  const supabase = createClient<Database>(
    Deno.env.get('SUPABASE_URL') ?? '',
    Deno.env.get('SUPABASE_ANON_KEY') ?? '',
    { global: { headers: { Authorization: c.req.header('Authorization') || '' } } }
  )
  
  const { data: { user }, error } = await supabase.auth.getUser()
  
  if (error || !user) {
    return c.json({ error: 'Unauthorized' }, 401)
  }

    .from('profiles')
    .select('role')
    .eq('id', user.id)
    .single()

  if (profile?.role !== 'admin') {
    return c.json({ error: 'Forbidden. Admin access required.' }, 403)
  }

  c.set('supabase', supabase)
  await next()
})

//Inventory Catalog
app.get('/inventory', async (c) => {
  const supabase = c.get('supabase')
const { data, error } = await supabase
    .from('inventory')
    .select('id, ingredient_name, current_stock, unit, reorder_level, supplier, expiry_date') 

  if (error) return c.json({ error: error.message }, 500)
  return c.json({ data }, 200)
})

Deno.serve(app.fetch)