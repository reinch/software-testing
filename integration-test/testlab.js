pm.test("Status code is 200", function (){
    pm.response.to.have.status(200);
});

pm.test("Each article has required fields", function(){
    const jsonData = pm.response.json();
    jsonData.articles.forEach(article => {
        pm.expect(article).to.have.property("title");
        pm.expect(article).to.have.property("description");
        pm.expect(article).to.have.property("url");
     });
});

//test case technology
pm.test("Status code is 200", function (){
    pm.response.to.have.status(200);
});

pm.test("Each article has required fields", function(){
    const jsonData = pm.response.json();
    jsonData.articles.forEach(article => {
        pm.expect(article).to.have.property("title");
        pm.expect(article).to.have.property("description");
        pm.expect(article).to.have.property("url");
     });
});