QUnit.test( "Plural of nouns FR", function( assert ) {
    assert.equal( N("chat").n('p'), "chats", "chat	chats" );
    assert.equal( N("cas").n('p'), "cas", "cas	cas" );
    assert.equal( N("château").n('p'), "châteaux", "château	châteaux" );
    assert.equal( N("cheval").n('p'), "chevaux", "cheval	chevaux" );
    assert.equal( N("époux").n('p'), "époux", "époux	époux" );
    assert.equal( N("gaz").n('p'), "gaz", "gaz	gaz" );
    assert.equal( N("bregh").n('p'), "breghs", "bregh	breghs" );
    assert.equal( N("jouet").n('p'), "jouets", "jouet	jouets" );
    assert.equal( N("poux").n('p'), "poux", "poux	poux" );
    assert.equal( N("nez").n('p'), "nez", "nez          nez" );
});