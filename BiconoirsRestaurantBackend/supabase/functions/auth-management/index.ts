import { Hono } from 'https://deno.land/x/hono@v3.4.1/mod.ts'
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2.39.0'
import { Database } from '../_shared/types/supabase.ts'

const supabase = createClient<Database>(
  Deno.env.get('SUPABASE_URL')!,
  Deno.env.get('SUPABASE_ANON_KEY')!
)

const app = new Hono()

app.get('/auth/me', async (c) => {
  const authHeader = c.req.header('Authorization')
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return c.json({ error: 'Missing or invalid Authorization header' }, 401)
  }

  const token = authHeader.slice(7)

  const { data: { user: authUser }, error: authError } = await supabase.auth.getUser(token)
  if (authError || !authUser) {
    return c.json({ error: 'Invalid or expired token' }, 401)
  }

  const { data: userData, error: dbError } = await supabase
    .from('users')
    .select('*')
    .eq('user_id', authUser.id)
    .single()

  if (dbError || !userData) {
    return c.json({ error: 'User not found in database' }, 404)
  }

  const { password_hash, ...safeUser } = userData

  return c.json(safeUser, 200)
})

app.get('/customers/:customerId/orders', async (c) => {
  const customerId = c.req.param('customerId')
  const status = c.req.query('status')

  const { data: userData, error: userError } = await supabase
    .from('users')
    .select('email')
    .eq('user_id', customerId)
    .single()

  if (userError || !userData) {
    return c.json({ error: 'Customer not found' }, 404)
  }

  let query = supabase
    .from('orders')
    .select('*')
    .eq('customer_email', userData.email)

  if (status) {
    query = query.eq('status', status)
  }

  const { data: orders } = await query

  return c.json(orders ?? [], 200)
})

export default app