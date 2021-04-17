( function() {
	let elements;
	let windowHeight;

	function init() {
		elements     = document.querySelectorAll( '.review-rating' );
		windowHeight = window.innerHeight;
	}

	function checkPosition() {
		for ( let i = 0; i < elements.length; i ++ ) {
			const element         = elements[ i ];
			const positionFromTop = elements[ i ].getBoundingClientRect().top;

			if ( positionFromTop - windowHeight <= 0 ) {
				element.classList.add( 'in-viewport' );
			}
		}
	}

	window.addEventListener( 'scroll', checkPosition );
	window.addEventListener( 'resize', init );

	init();
	checkPosition();
} )();
