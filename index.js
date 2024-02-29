panel.plugin('visionbites/usage-reference', {
	sections: {
		usageReference: {
			data: function () {
				return {
					headline: null,
					references: Array,
					items: Array
				}
			},

			created: function () {
				this.load().then(response => {
					this.headline = response.headline;
					this.references = response.references;
					let items = [];

					this.references.forEach(reference => {
						items.push({
							preview: [
								{
									image: {
										icon: "page",
										back: "black",
										color: "white"
									},
									text: reference.title,
									link: reference.breadcrumb[reference.breadcrumb.length - 1].link,
								}
							],
							title: reference.title,
							template: reference.template,
							breadcrumb: reference.breadcrumb,
						})
					});

					this.items = items;
				});
			},

			template: `
<template>
  <section class="k-usage-references-section">
    <header v-if="headline" class="k-section-header">
      <k-headline>{{ headline }}</k-headline>
    </header>
    <template v-if="references">
      <div class="k-table">
        <table  >
          <thead>
          <tr>
            <th class="k-table-column" data-mobile="true">
              title
            </th>
            <th class="k-table-column" data-mobile="true">
              template
            </th>
            <th class="k-table-column" data-mobile="true">
              page
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="reference in items">
            <td class="k-table-column " data-mobile="true" style="padding-left: 0.75em">
              {{ reference.title }}
            </td>
            <td class="k-table-column"  data-mobile="true" style="padding-left: 0.75em">
              {{ reference.template }}
            </td>
            <td class="k-table-column" data-mobile="true">
              <k-pages-field-preview :value="reference.preview"/>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </template>
  </section>
</template>`
		}
	}
});
