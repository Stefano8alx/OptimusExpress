// supabaseClient.js
const { createClient } = require('@supabase/supabase-js');

// Estas variables se definirán en un archivo .env local y en Netlify como variables de entorno.
const supabaseUrl = process.env.SUPABASE_URL;
const supabaseKey = process.env.SUPABASE_KEY;

const supabase = createClient(supabaseUrl, supabaseKey);

module.exports = { supabase };
