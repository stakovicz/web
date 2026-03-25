Feature: Site Public - Feuilles

  @reloadDbWithTestData
  Scenario: Les feuilles sont affichées selon leur date de publication
    Given I am on the homepage
    Then I should not see "Feuille publication passée"
    And I should not see "Feuille publication future"
    And I should see "Feuille publication courante"
