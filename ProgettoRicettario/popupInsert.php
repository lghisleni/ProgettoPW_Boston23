<div class="popupInsert" id="popupInsert">
    <img src="imm/insert.png"></img>
    <h2> Per inserire correttamente l'ingrediente specificare un numero ricetta valido </p>
    <form name="formInsert" id="formInsert" onsubmit="return handleFormInsert(event)" method="POST">
        <input id="numeroRicettaIns" name="nomeRicettaIns" type="text" placeholder="Inserisci il numero ricetta"/>
        <input id="nomeIngredienteIns" name="nomeIngredienteIns" type="text" placeholder="Inserisci il nome"/>
        <input id="quantitaIns" name="quantitaIns" type="text" placeholder="Inserisci la quantità"/>
        <input id="insert" type="submit" value="Insert"/>
    </form>
    <button class="btnBackInser" onclick="annulaInsert()"> Annulla </button>
</div>