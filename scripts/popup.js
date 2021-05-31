( function() {
	const popupContainer = document.getElementsByClassName( 'popup-container' )[ 0 ];
	const popup          = document.getElementsByClassName( 'popup' )[ 0 ];
	const language       = document.documentElement.getAttribute( 'lang' ).split( '-' )[ 0 ];
	const cookieName     = 'popup-' + language + '-closed';

	document.addEventListener( 'mouseout', function _listener( event ) {
		if ( event.toElement === null && event.relatedTarget === null && ! getCookie( cookieName ) ) {
			fadeIn( popupContainer );
			document.removeEventListener( 'mouseout', _listener, true );
		}
	} );

	document.addEventListener( 'click', function( event ) {
		if ( popup !== event.target && ! popup.contains( event.target ) ) {
			fadeOut( popupContainer );
			setCookie( cookieName, true, 1 );
		}
	} );

	function fadeOut( el ) {
		el.style.opacity = 1;

		( function fade() {
			if ( ( el.style.opacity -= .1 ) < 0 ) {
				el.style.display = 'none';
			} else {
				requestAnimationFrame( fade );
			}
		} )();
	}

	function fadeIn( el, display ) {
		el.style.opacity = 0;
		el.style.display = display || 'flex';

		( function fade() {
			var val = parseFloat( el.style.opacity );
			if ( ! ( ( val += .1 ) > 1 ) ) {
				el.style.opacity = val;
				requestAnimationFrame( fade );
			}
		} )();
	}

	function setCookie( value, days ) {
		let expires = '';

		if ( days ) {
			const date = new Date();

			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			expires = '; expires=' + date.toUTCString();
		}

		document.cookie = cookieName + '=' + ( value || '' ) + expires + '; path=/';
	}

	function getCookie() {
		const nameEQ = cookieName + '=';
		const ca     = document.cookie.split( ';' );

		for ( let i = 0; i < ca.length; i ++ ) {
			let c = ca[ i ];

			while ( c.charAt( 0 ) === ' ' ) {
				c = c.substring( 1, c.length );
			}

			if ( c.indexOf( nameEQ ) === 0 ) {
				return c.substring( nameEQ.length, c.length );
			}
		}

		return null;
	}

	function eraseCookie() {
		document.cookie = cookieName + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	}
} )();
