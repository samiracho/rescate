Ext.onReady(function ()
{
    // borramos la máscara del body
    Ext.get('loading').remove();
    Ext.get('loading-mask').fadeOut(
    {
        remove: true
    });
    // Creamos una variable que contenga nuestro EXT FormPanel.
    // Asignamos varias opciones de configuración.
    var login = new Ext.FormPanel(
    {
        labelWidth: 80,
        url: 'datos/login.php',
        frame: true,
        title: 'Por favor, identifíquese',
        defaultType: 'textfield',
        monitorValid: true,
        // Atributo específico para los campos usuario/clave.
        // El atributo "name" defiene el nombre de la variable enviada al servidor. 
        items: [
        {
            fieldLabel: 'Usuario',
            name: 'loginUsername',
            itemId: 'loginUsername',
            allowBlank: false
        }, {
            fieldLabel: 'Clave',
            name: 'loginPassword',
            inputType: 'password',
            allowBlank: false
        }],
        // Toda la mágia ocurre después de que el usuario pulse el botón.
        buttons: [
        {
            text: 'Login',
            formBind: true,
            itemId: 'submitLogin',
            // Función que se ejecuta cuando el usuario pulsa el botón.
            handler: function ()
            {
                login.getForm().submit(
                {
                    method: 'POST',
                    waitTitle: 'Conectando',
                    waitMsg: 'Enviando datos...',
                    // Función que se ejecuta (éxito o fracaso) cuando el servidor responde.
                    // Cual se ejecuta es determinado por la respuesta
                    // proveniente de login.asp como se muestra abajo. El servidor responde
                    // realmente con un JSON valido, algo como: response.write "{succes: true}"
                    // o response.write "{succes: false, errors: { reason: 'Identificación incorrecta. Inténtelo de nuevo.' }}"
                    // dependiendo en la lógica contenida en su servidor.
                    // Si tiene éxito, se notifica al usuario con un messagebox de alerta,
                    // y cuando se pulsa "OK", eres redirigido a cualquier página
                    // que haya elegido.
                    success: function ()
                    {
                        var redirect = 'index.php';
                        window.location = redirect;
                    },
/*
			success:function(){ 
                   Ext.Msg.alert('Estatus', 'Identificación correcta', function(btn, text){
						if (btn == 'ok'){
							var redirect = 'index.php'; 
							window.location = redirect;
						}
			        });
            },
			*/
                    // Función de fallo, see comment above re: éxito y fallo.
                    // Como puede ver aquí, si la identificación falla, lanza
                    // un mensaje al usuario con los detalles del fallo.
                    failure: function (form, action)
                    {
                        if (action.failureType == 'server')
                        {
                            obj = Ext.decode(action.response.responseText);
                            Ext.Msg.alert('Identificación incorrecta', obj.errors.reason);
                        }
                        else
                        {
                            Ext.Msg.alert('¡Atención!', 'Fallo de conexión con el servidor de autenticación: ' + action.response.responseText);
                        }
                        login.getForm().reset();
                    }
                });
            }
        }],
        listeners: {
            afterrender: function ()
            {
                login.down('#loginUsername').focus(false, 1000)
            }
        },
        keys: new Ext.KeyMap(document, {
            key: Ext.EventObject.ENTER,
            fn: function ()
            {
                login.down('#submitLogin').focus();
            },
            scope: this
        })
    });
    // Esto sólo crea una ventana para envolver el formulario de registro.
    // El objeto login se pasa a la colección de items.
    var win = new Ext.Window(
    {
        layout: 'fit',
        width: 300,
        height: 150,
        closable: false,
        resizable: false,
        plain: true,
        border: false,
        items: [login]
    });
    win.show();
});