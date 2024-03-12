<style>
    
/* Global Styles */
:root {
  --primary-bg: #282c34; /* Dark background color */
  --secondary-bg: #3e4451; /* Box background color */
  --accent-color: #61dafb; /* Accent color */
  --text-color: #ccc; /* Text color */
}

/* Alert Box Styles */
.alert-box-div {
  background-color: var(--primary-bg);
  width: 100vw;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.alert-box {
  height: fit-content;
  max-width: 70vw;
  width: 100%;
  background: var(--secondary-bg);
  color: var(--text-color);
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: auto;
  border-radius: 0.5rem;
  box-sizing: border-box;
  padding: 20px;
  font-size: 1.3rem;
  word-spacing: 5px;
  letter-spacing: 2px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
}

/* News Title */
.alert-box h2 {
  font-size: 1.8rem;
  margin-bottom: 10px;
  color: var(--accent-color);
}

/* News Content */
.alert-box .scrollable-content {
  max-height: 200px; /* Adjust the max height as needed */
  overflow-y: auto;
}

.alert-box p {
  line-height: 1.6;
  color: var(--text-color);
  margin-bottom: 15px;
}

/* Time Stamp */
.alert-box span {
  font-size: 0.9rem;
  color: #888;
}

</style>



<div class="alert-box-div">
    <div class="alert-box">
            <div class="news-container">



                <form method="POST" action="/news/read/next">
                    @csrf
                    <button type="submit" class="submit-nxt">Refresh</button>
                </form>
            </div>
    </div>
</div>





