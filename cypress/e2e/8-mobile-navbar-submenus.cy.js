describe("Mobile navbar submenu CSS", () => {
	it("defines collapsed-navbar overrides below the lg breakpoint", () => {
		cy.readFile("assets/css/general.css", { timeout: 10000 }).then((css) => {
			expect(css).to.include("@media (max-width: 991.98px)");
			expect(css).to.include(
				".navbar-expand-lg .navbar-nav .dropdown-submenu > .dropdown-menu",
			);
			expect(css).to.match(
				/\.navbar-expand-lg \.navbar-nav \.dropdown-menu[\s\S]*position:\s*static\s*!important/,
			);
			expect(css).to.match(
				/\.navbar-expand-lg \.navbar-nav \.dropdown-submenu > \.dropdown-menu[\s\S]*position:\s*static\s*!important/,
			);
			expect(css).to.include("calc(100dvh - 4rem)");
		});
	});
});
