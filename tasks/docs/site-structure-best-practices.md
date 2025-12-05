# Site Structure Best Practices

## Quick Reference

### ✅ DO

**1. Define Everything Upfront**
```json
{
  "siteStructure": {
    "pages": [...],
    "postTypes": [...],
    "taxonomies": [...],
    "menus": [...],
    "settings": {...}
  }
}
```

**2. Use Publish Status**
```json
{ "status": "publish" }  // Pages go live immediately
```

**3. Pre-create Taxonomy Terms**
```json
{
  "taxonomies": [{
    "terms": [
      {"name": "Technology", "slug": "technology"},
      {"name": "Healthcare", "slug": "healthcare"}
    ]
  }]
}
```

**4. Link CPTs and Taxonomies**
```json
{
  "postTypes": [{"name": "service", "taxonomies": ["service-category"]}],
  "taxonomies": [{"name": "service-category", "postTypes": ["service"]}]
}
```

**5. Use Proper Menu Item Types**
- `page` - WordPress pages
- `post_type_archive` - CPT archives
- `taxonomy` - Taxonomy term pages
- `custom` - External/custom URLs

**6. Extract Real Images from Figma**
Always set to extract actual images, not placeholders.

---

## ❌ DON'T

**1. Don't Use Draft Status by Default**
```json
// ❌ Bad - requires manual publish
{ "status": "draft" }

// ✅ Good - ready immediately
{ "status": "publish" }
```

**2. Don't Leave Taxonomies Empty**
```json
// ❌ Bad - no terms to assign
{
  "taxonomies": [{"name": "category", "terms": []}]
}

// ✅ Good - pre-populated
{
  "taxonomies": [{
    "name": "category",
    "terms": [
      {"name": "News", "slug": "news"},
      {"name": "Updates", "slug": "updates"}
    ]
  }]
}
```

**3. Don't Forget Post Type Supports**
```json
// ❌ Bad - missing features
{
  "postTypes": [{"name": "service", "supports": []}]
}

// ✅ Good - full features
{
  "postTypes": [{
    "name": "service",
    "supports": ["title", "editor", "thumbnail", "excerpt"]
  }]
}
```

**4. Don't Use Placeholders**
- ❌ Placeholder images
- ✅ Extract actual images from Figma

**5. Don't Hardcode Settings**
```json
// ✅ Good - configurable
{
  "settings": {
    "blogName": "Your Company",
    "timezone": "Europe/London"
  }
}
```

---

## Common Patterns

### Pattern 1: Service-Based Site
```json
{
  "pages": [
    {"slug": "home", "isHomepage": true},
    {"slug": "services"}
  ],
  "postTypes": [{
    "name": "service",
    "hasArchive": true,
    "taxonomies": ["service-category"]
  }],
  "taxonomies": [{
    "name": "service-category",
    "hierarchical": true,
    "terms": [
      {"name": "Consulting", "slug": "consulting"},
      {"name": "Development", "slug": "development"}
    ]
  }],
  "menus": [{
    "items": [
      {"type": "page", "slug": "services"},
      {"type": "post_type_archive", "postType": "service", "label": "All Services"}
    ]
  }]
}
```

### Pattern 2: Blog with Categories
```json
{
  "postTypes": [{
    "name": "post",
    "taxonomies": ["category", "post_tag"]
  }],
  "taxonomies": [
    {
      "name": "category",
      "hierarchical": true,
      "terms": [
        {"name": "Tech", "slug": "tech"},
        {"name": "Business", "slug": "business", "parent": "tech"}
      ]
    },
    {
      "name": "post_tag",
      "hierarchical": false,
      "terms": [
        {"name": "featured", "slug": "featured"},
        {"name": "trending", "slug": "trending"}
      ]
    }
  ]
}
```

### Pattern 3: Portfolio Site
```json
{
  "postTypes": [{
    "name": "project",
    "hasArchive": true,
    "supports": ["title", "editor", "thumbnail"],
    "taxonomies": ["project-type", "industry"]
  }],
  "taxonomies": [
    {
      "name": "project-type",
      "hierarchical": false,
      "terms": [
        {"name": "Web Design", "slug": "web-design"},
        {"name": "Branding", "slug": "branding"}
      ]
    },
    {
      "name": "industry",
      "hierarchical": true,
      "terms": [
        {"name": "Technology", "slug": "technology"},
        {"name": "Healthcare", "slug": "healthcare"}
      ]
    }
  ],
  "menus": [{
    "items": [
      {"type": "post_type_archive", "postType": "project", "label": "Portfolio"},
      {"type": "taxonomy", "taxonomy": "project-type", "slug": "web-design", "label": "Web Design"}
    ]
  }]
}
```

