/*-------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------ OVERRIDES ----------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------*/
// para mostrar un mensaje cuando la respuesta json del servidor es incorrecta
(function ()
{
    var oldDc = Ext.JSON.decode;
    //if (oldDc.toString().indexOf("safe") === -1) {
    Ext.JSON.decode = function (json, safe)
    {
        try
        {
            return oldDc(json);
        }
        catch (e)
        {
            // quitamos la m�scara si la hubiera
            Ext.getBody().unmask();
            // construimos un objeto con el error
            var respuesta =
            {
                message: t('REMOTE EXCEPTION'),
                errors: t('WRONG SERVER RESPONSE') + '<br /><br />' + t('Server Response') + ':<div style="height:100px;overflow:auto">' + json + '</div>',
                success: false
            };
            return respuesta;
        }
    };
    Ext.decode = Ext.JSON.decode;
    //}
}());

/*Para solucionar el error de traducción de los messagebox*/
if (Ext.MessageBox)
{
    Ext.MessageBox.msgButtons['ok'].text = t('Ok');
    Ext.MessageBox.msgButtons['cancel'].text = t('Cancel');
    Ext.MessageBox.msgButtons['yes'].text = t('Yes');
    Ext.MessageBox.msgButtons['no'].text = t('No');
}

// fallo al reconfigurar un checkboxgroup en 4.1rc1
Ext.override(Ext.layout.container.CheckboxGroup, {
    fixColumns: function () {
	
        var me = this,
            owner = me.owner,
			//columns = me.columns,
            columns = me.columnEls,
            items = owner.items.items,
            columnCount = columns.length,
            itemCount = items.length,
            columnIndex, i, rowCount;

        if (owner.vertical) {
            columnIndex = -1; 
            rowCount = Math.ceil(itemCount / columnCount);

            for (i = 0; i < itemCount; ++i) {
                if (i % rowCount === 0) {
                    ++columnIndex;
                }
                columns[columnIndex].appendChild(items[i].el.dom);
            }
        } else {
            for (i = 0; i < itemCount; ++i) {
                columnIndex = i % columnCount;
                columns[columnIndex].appendChild(items[i].el.dom);
            }
        }
    }
});