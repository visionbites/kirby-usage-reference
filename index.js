panel.plugin('visionbites/usage-reference', {
  sections: {
    usageReference: {
      data: function () {
        return {
          headline: null,
          references: Array
        }
      },

      created: function() {
        this.load().then(response => {
          this.headline = response.headline;
          this.references = response.references;
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
            <th class="k-table-column" data-mobile>
              title
            </th>
            <th class="k-table-column" data-mobile>
              template
            </th>
            <th class="k-table-column" data-mobile>
              breadcrumb
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="reference in references">
            <td class="k-table-column " data-mobile style="padding-left: 0.75em">
              {{ reference.title }}
            </td>
            <td class="k-table-column"  data-mobile style="padding-left: 0.75em">
              {{ reference.template }}
            </td>
            <td class="k-table-column" data-mobile>
              <k-breadcrumb :crumbs="reference.breadcrumb" view="table" />
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
