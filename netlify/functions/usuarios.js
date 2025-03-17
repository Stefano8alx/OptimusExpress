// netlify/functions/usuarios.js
const { supabase } = require('../../supabaseClient'); // Asegúrate de ajustar la ruta según donde lo ubiques

exports.handler = async function(event, context) {
  try {
    // Aquí puedes parsear 'event' según tus necesidades (por ejemplo, si necesitas datos del querystring o body)
    const { data: usuarios, error } = await supabase.from('usuarios').select('*');
    if (error) throw error;
    return {
      statusCode: 200,
      body: JSON.stringify({ usuarios }),
    };
  } catch (error) {
    return {
      statusCode: 500,
      body: JSON.stringify({ error: error.message }),
    };
  }
};
