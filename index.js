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
            <th class="k-table-column">
              title
            </th>
            <th class="k-table-column">
              template
            </th>
            <th class="k-table-column">
              breadcrumb
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="reference in references">
            <td class="k-table-column " style="padding-left: 0.75em">
              {{ reference.title }}
            </td>
            <td class="k-table-column"  style="padding-left: 0.75em">
              {{ reference.template }}
            </td>
            <td class="k-table-column">
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
