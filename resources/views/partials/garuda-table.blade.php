<style>
  .garuda-container {
    overflow-y: scroll;
  }

  #garuda-front-table, #garuda-back-table {
    width: 1300px !important;
    table-layout: fixed;
  }

  #garuda-front-table tbody {
    background-image: url("{{ asset('assets/img/garuda-front.jpg') }}");
    background-repeat: no-repeat;
    height: 563px;
  }

  #garuda-back-table tbody {
    background-image: url("{{ asset('assets/img/garuda-back.jpg') }}");
    background-repeat: no-repeat;
    height: 563px;
  }

  #garuda-front-table tr:hover, #garuda-back-table tr:hover {
    background: none !important;
  }

  #garuda-front-segment-table tbody, #garuda-back-segment-table tbody {
    position: sticky;
    left: 0;
    overflow-x: auto;
    z-index: 10;
    background: #FAFBFE;
    height: 563px;
  }
  
  #garuda-front-segment-table td, #garuda-back-segment-table td {
    /* border: 1px solid black; */
    padding: 5px;
  }

  #garuda-front-table td, #garuda-back-table td {
    border: 1px solid black;
    text-align: left;
    padding: 5px;
  }

  #garuda-front-table tfoot td, #garuda-back-table tfoot td {
    text-align: center;
    border: none;
  }

  #table-agregat-status,
  #table-agregat-status th,
  #table-agregat-status td,
  #table-agregat-segment,
  #table-agregat-segment th,
  #table-agregat-segment td {
    border: 1px #DEE2E6 solid;
    vertical-align: middle;
    overflow-y: hidden !important;
  }
</style>

<div class="garuda-container">
  <div class="pb-4 d-flex gap-5">
    <div>
      <div class="d-flex">
        <table id="garuda-front-segment-table">
          <tbody>
            <tr>
              <td>16</td>
            </tr>
            <tr>
              <td>15</td>
            </tr>
            <tr>
              <td>14</td>
            </tr>
            <tr>
              <td>13</td>
            </tr>
            <tr>
              <td>12</td>
            </tr>
            <tr>
              <td>11</td>
            </tr>
            <tr>
              <td>10</td>
            </tr>
            <tr>
              <td>09</td>
            </tr>
            <tr>
              <td>08</td>
            </tr>
            <tr>
              <td>07</td>
            </tr>
            <tr>
              <td>06</td>
            </tr>
            <tr>
              <td>05</td>
            </tr>
            <tr>
              <td>04</td>
            </tr>
            <tr>
              <td>03</td>
            </tr>
            <tr>
              <td>02</td>
            </tr>
            <tr>
              <td>01</td>
            </tr>
            <tr>
              <td>00</td>
            </tr>
            <tr>
              <td>S/M</td>
            </tr>
          </tbody>
        </table>
        <table id="garuda-front-table">
          <tbody>
            <tr>
              <td class="S16 M11"></td>
              <td class="S16 M10"></td>
              <td class="S16 M09"></td>
              <td class="S16 M08"></td>
              <td class="S16 M07"></td>
              <td class="S16 M06"></td>
              <td class="S16 M05"></td>
              <td class="S16 M04"></td>
              <td class="S16 M03"></td>
              <td class="S16 M02"></td>
              <td class="S16 M01"></td>
              <td class="S16 M44"></td>
              <td class="S16 M43"></td>
              <td class="S16 M42"></td>
              <td class="S16 M41"></td>
              <td class="S16 M40"></td>
              <td class="S16 M39"></td>
              <td class="S16 M38"></td>
              <td class="S16 M37"></td>
              <td class="S16 M36"></td>
              <td class="S16 M35"></td>
              <td class="S16 M34"></td>
            </tr>
            <tr>
              <td class="S15 M11"></td>
              <td class="S15 M10"></td>
              <td class="S15 M09"></td>
              <td class="S15 M08"></td>
              <td class="S15 M07"></td>
              <td class="S15 M06"></td>
              <td class="S15 M05"></td>
              <td class="S15 M04"></td>
              <td class="S15 M03"></td>
              <td class="S15 M02"></td>
              <td class="S15 M01"></td>
              <td class="S15 M44"></td>
              <td class="S15 M43"></td>
              <td class="S15 M42"></td>
              <td class="S15 M41"></td>
              <td class="S15 M40"></td>
              <td class="S15 M39"></td>
              <td class="S15 M38"></td>
              <td class="S15 M37"></td>
              <td class="S15 M36"></td>
              <td class="S15 M35"></td>
              <td class="S15 M34"></td>
            </tr>
            <tr>
              <td class="S14 M11"></td>
              <td class="S14 M10"></td>
              <td class="S14 M09"></td>
              <td class="S14 M08"></td>
              <td class="S14 M07"></td>
              <td class="S14 M06"></td>
              <td class="S14 M05"></td>
              <td class="S14 M04"></td>
              <td class="S14 M03"></td>
              <td class="S14 M02"></td>
              <td class="S14 M01"></td>
              <td class="S14 M44"></td>
              <td class="S14 M43"></td>
              <td class="S14 M42"></td>
              <td class="S14 M41"></td>
              <td class="S14 M40"></td>
              <td class="S14 M39"></td>
              <td class="S14 M38"></td>
              <td class="S14 M37"></td>
              <td class="S14 M36"></td>
              <td class="S14 M35"></td>
              <td class="S14 M34"></td>
            </tr>
            <tr>
              <td class="S13 M11"></td>
              <td class="S13 M10"></td>
              <td class="S13 M09"></td>
              <td class="S13 M08"></td>
              <td class="S13 M07"></td>
              <td class="S13 M06"></td>
              <td class="S13 M05"></td>
              <td class="S13 M04"></td>
              <td class="S13 M03"></td>
              <td class="S13 M02"></td>
              <td class="S13 M01"></td>
              <td class="S13 M44"></td>
              <td class="S13 M43"></td>
              <td class="S13 M42"></td>
              <td class="S13 M41"></td>
              <td class="S13 M40"></td>
              <td class="S13 M39"></td>
              <td class="S13 M38"></td>
              <td class="S13 M37"></td>
              <td class="S13 M36"></td>
              <td class="S13 M35"></td>
              <td class="S13 M34"></td>
            </tr>
            <tr>
              <td class="S12 M11"></td>
              <td class="S12 M10"></td>
              <td class="S12 M09"></td>
              <td class="S12 M08"></td>
              <td class="S12 M07"></td>
              <td class="S12 M06"></td>
              <td class="S12 M05"></td>
              <td class="S12 M04"></td>
              <td class="S12 M03"></td>
              <td class="S12 M02"></td>
              <td class="S12 M01"></td>
              <td class="S12 M44"></td>
              <td class="S12 M43"></td>
              <td class="S12 M42"></td>
              <td class="S12 M41"></td>
              <td class="S12 M40"></td>
              <td class="S12 M39"></td>
              <td class="S12 M38"></td>
              <td class="S12 M37"></td>
              <td class="S12 M36"></td>
              <td class="S12 M35"></td>
              <td class="S12 M34"></td>
            </tr>
            <tr>
              <td class="S11 M11"></td>
              <td class="S11 M10"></td>
              <td class="S11 M09"></td>
              <td class="S11 M08"></td>
              <td class="S11 M07"></td>
              <td class="S11 M06"></td>
              <td class="S11 M05"></td>
              <td class="S11 M04"></td>
              <td class="S11 M03"></td>
              <td class="S11 M02"></td>
              <td class="S11 M01"></td>
              <td class="S11 M44"></td>
              <td class="S11 M43"></td>
              <td class="S11 M42"></td>
              <td class="S11 M41"></td>
              <td class="S11 M40"></td>
              <td class="S11 M39"></td>
              <td class="S11 M38"></td>
              <td class="S11 M37"></td>
              <td class="S11 M36"></td>
              <td class="S11 M35"></td>
              <td class="S11 M34"></td>
            </tr>
            <tr>
              <td class="S10 M11"></td>
              <td class="S10 M10"></td>
              <td class="S10 M09"></td>
              <td class="S10 M08"></td>
              <td class="S10 M07"></td>
              <td class="S10 M06"></td>
              <td class="S10 M05"></td>
              <td class="S10 M04"></td>
              <td class="S10 M03"></td>
              <td class="S10 M02"></td>
              <td class="S10 M01"></td>
              <td class="S10 M44"></td>
              <td class="S10 M43"></td>
              <td class="S10 M42"></td>
              <td class="S10 M41"></td>
              <td class="S10 M40"></td>
              <td class="S10 M39"></td>
              <td class="S10 M38"></td>
              <td class="S10 M37"></td>
              <td class="S10 M36"></td>
              <td class="S10 M35"></td>
              <td class="S10 M34"></td>
            </tr>
            <tr>
              <td class="S09 M11"></td>
              <td class="S09 M10"></td>
              <td class="S09 M09"></td>
              <td class="S09 M08"></td>
              <td class="S09 M07"></td>
              <td class="S09 M06"></td>
              <td class="S09 M05"></td>
              <td class="S09 M04"></td>
              <td class="S09 M03"></td>
              <td class="S09 M02"></td>
              <td class="S09 M01"></td>
              <td class="S09 M44"></td>
              <td class="S09 M43"></td>
              <td class="S09 M42"></td>
              <td class="S09 M41"></td>
              <td class="S09 M40"></td>
              <td class="S09 M39"></td>
              <td class="S09 M38"></td>
              <td class="S09 M37"></td>
              <td class="S09 M36"></td>
              <td class="S09 M35"></td>
              <td class="S09 M34"></td>
            </tr>
            <tr>
              <td class="S08 M11"></td>
              <td class="S08 M10"></td>
              <td class="S08 M09"></td>
              <td class="S08 M08"></td>
              <td class="S08 M07"></td>
              <td class="S08 M06"></td>
              <td class="S08 M05"></td>
              <td class="S08 M04"></td>
              <td class="S08 M03"></td>
              <td class="S08 M02"></td>
              <td class="S08 M01"></td>
              <td class="S08 M44"></td>
              <td class="S08 M43"></td>
              <td class="S08 M42"></td>
              <td class="S08 M41"></td>
              <td class="S08 M40"></td>
              <td class="S08 M39"></td>
              <td class="S08 M38"></td>
              <td class="S08 M37"></td>
              <td class="S08 M36"></td>
              <td class="S08 M35"></td>
              <td class="S08 M34"></td>
            </tr>
            <tr>
              <td class="S07 M11"></td>
              <td class="S07 M10"></td>
              <td class="S07 M09"></td>
              <td class="S07 M08"></td>
              <td class="S07 M07"></td>
              <td class="S07 M06"></td>
              <td class="S07 M05"></td>
              <td class="S07 M04"></td>
              <td class="S07 M03"></td>
              <td class="S07 M02"></td>
              <td class="S07 M01"></td>
              <td class="S07 M44"></td>
              <td class="S07 M43"></td>
              <td class="S07 M42"></td>
              <td class="S07 M41"></td>
              <td class="S07 M40"></td>
              <td class="S07 M39"></td>
              <td class="S07 M38"></td>
              <td class="S07 M37"></td>
              <td class="S07 M36"></td>
              <td class="S07 M35"></td>
              <td class="S07 M34"></td>
            </tr>
            <tr>
              <td class="S06 M11"></td>
              <td class="S06 M10"></td>
              <td class="S06 M09"></td>
              <td class="S06 M08"></td>
              <td class="S06 M07"></td>
              <td class="S06 M06"></td>
              <td class="S06 M05"></td>
              <td class="S06 M04"></td>
              <td class="S06 M03"></td>
              <td class="S06 M02"></td>
              <td class="S06 M01"></td>
              <td class="S06 M44"></td>
              <td class="S06 M43"></td>
              <td class="S06 M42"></td>
              <td class="S06 M41"></td>
              <td class="S06 M40"></td>
              <td class="S06 M39"></td>
              <td class="S06 M38"></td>
              <td class="S06 M37"></td>
              <td class="S06 M36"></td>
              <td class="S06 M35"></td>
              <td class="S06 M34"></td>
            </tr>
            <tr>
              <td class="S05 M11"></td>
              <td class="S05 M10"></td>
              <td class="S05 M09"></td>
              <td class="S05 M08"></td>
              <td class="S05 M07"></td>
              <td class="S05 M06"></td>
              <td class="S05 M05"></td>
              <td class="S05 M04"></td>
              <td class="S05 M03"></td>
              <td class="S05 M02"></td>
              <td class="S05 M01"></td>
              <td class="S05 M44"></td>
              <td class="S05 M43"></td>
              <td class="S05 M42"></td>
              <td class="S05 M41"></td>
              <td class="S05 M40"></td>
              <td class="S05 M39"></td>
              <td class="S05 M38"></td>
              <td class="S05 M37"></td>
              <td class="S05 M36"></td>
              <td class="S05 M35"></td>
              <td class="S05 M34"></td>
            </tr>
            <tr>
              <td class="S04 M11"></td>
              <td class="S04 M10"></td>
              <td class="S04 M09"></td>
              <td class="S04 M08"></td>
              <td class="S04 M07"></td>
              <td class="S04 M06"></td>
              <td class="S04 M05"></td>
              <td class="S04 M04"></td>
              <td class="S04 M03"></td>
              <td class="S04 M02"></td>
              <td class="S04 M01"></td>
              <td class="S04 M44"></td>
              <td class="S04 M43"></td>
              <td class="S04 M42"></td>
              <td class="S04 M41"></td>
              <td class="S04 M40"></td>
              <td class="S04 M39"></td>
              <td class="S04 M38"></td>
              <td class="S04 M37"></td>
              <td class="S04 M36"></td>
              <td class="S04 M35"></td>
              <td class="S04 M34"></td>
            </tr>
            <tr>
              <td class="S03 M11"></td>
              <td class="S03 M10"></td>
              <td class="S03 M09"></td>
              <td class="S03 M08"></td>
              <td class="S03 M07"></td>
              <td class="S03 M06"></td>
              <td class="S03 M05"></td>
              <td class="S03 M04"></td>
              <td class="S03 M03"></td>
              <td class="S03 M02"></td>
              <td class="S03 M01"></td>
              <td class="S03 M44"></td>
              <td class="S03 M43"></td>
              <td class="S03 M42"></td>
              <td class="S03 M41"></td>
              <td class="S03 M40"></td>
              <td class="S03 M39"></td>
              <td class="S03 M38"></td>
              <td class="S03 M37"></td>
              <td class="S03 M36"></td>
              <td class="S03 M35"></td>
              <td class="S03 M34"></td>
            </tr>
            <tr>
              <td class="S02 M11"></td>
              <td class="S02 M10"></td>
              <td class="S02 M09"></td>
              <td class="S02 M08"></td>
              <td class="S02 M07"></td>
              <td class="S02 M06"></td>
              <td class="S02 M05"></td>
              <td class="S02 M04"></td>
              <td class="S02 M03"></td>
              <td class="S02 M02"></td>
              <td class="S02 M01"></td>
              <td class="S02 M44"></td>
              <td class="S02 M43"></td>
              <td class="S02 M42"></td>
              <td class="S02 M41"></td>
              <td class="S02 M40"></td>
              <td class="S02 M39"></td>
              <td class="S02 M38"></td>
              <td class="S02 M37"></td>
              <td class="S02 M36"></td>
              <td class="S02 M35"></td>
              <td class="S02 M34"></td>
            </tr>
            <tr>
              <td class="S01 M11"></td>
              <td class="S01 M10"></td>
              <td class="S01 M09"></td>
              <td class="S01 M08"></td>
              <td class="S01 M07"></td>
              <td class="S01 M06"></td>
              <td class="S01 M05"></td>
              <td class="S01 M04"></td>
              <td class="S01 M03"></td>
              <td class="S01 M02"></td>
              <td class="S01 M01"></td>
              <td class="S01 M44"></td>
              <td class="S01 M43"></td>
              <td class="S01 M42"></td>
              <td class="S01 M41"></td>
              <td class="S01 M40"></td>
              <td class="S01 M39"></td>
              <td class="S01 M38"></td>
              <td class="S01 M37"></td>
              <td class="S01 M36"></td>
              <td class="S01 M35"></td>
              <td class="S01 M34"></td>
            </tr>
            <tr>
              <td class="S00 M11"></td>
              <td class="S00 M10"></td>
              <td class="S00 M09"></td>
              <td class="S00 M08"></td>
              <td class="S00 M07"></td>
              <td class="S00 M06"></td>
              <td class="S00 M05"></td>
              <td class="S00 M04"></td>
              <td class="S00 M03"></td>
              <td class="S00 M02"></td>
              <td class="S00 M01"></td>
              <td class="S00 M44"></td>
              <td class="S00 M43"></td>
              <td class="S00 M42"></td>
              <td class="S00 M41"></td>
              <td class="S00 M40"></td>
              <td class="S00 M39"></td>
              <td class="S00 M38"></td>
              <td class="S00 M37"></td>
              <td class="S00 M36"></td>
              <td class="S00 M35"></td>
              <td class="S00 M34"></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>11</td>
              <td>10</td>
              <td>09</td>
              <td>08</td>
              <td>07</td>
              <td>06</td>
              <td>05</td>
              <td>04</td>
              <td>03</td>
              <td>02</td>
              <td>01</td>
              <td>44</td>
              <td>43</td>
              <td>42</td>
              <td>41</td>
              <td>40</td>
              <td>39</td>
              <td>38</td>
              <td>37</td>
              <td>36</td>
              <td>35</td>
              <td>34</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <h4 class="text-center">Tampak Depan</h4>
    </div>
    <div>
      <div class="d-flex">
        <table id="garuda-back-segment-table">
          <tbody>
            <tr>
              <td>16</td>
            </tr>
            <tr>
              <td>15</td>
            </tr>
            <tr>
              <td>14</td>
            </tr>
            <tr>
              <td>13</td>
            </tr>
            <tr>
              <td>12</td>
            </tr>
            <tr>
              <td>11</td>
            </tr>
            <tr>
              <td>10</td>
            </tr>
            <tr>
              <td>09</td>
            </tr>
            <tr>
              <td>08</td>
            </tr>
            <tr>
              <td>07</td>
            </tr>
            <tr>
              <td>06</td>
            </tr>
            <tr>
              <td>05</td>
            </tr>
            <tr>
              <td>04</td>
            </tr>
            <tr>
              <td>03</td>
            </tr>
            <tr>
              <td>02</td>
            </tr>
            <tr>
              <td>01</td>
            </tr>
            <tr>
              <td>00</td>
            </tr>
            <tr>
              <td>S/M</td>
            </tr>
          </tbody>
        </table>
        <table id="garuda-back-table">
          <tbody>
            <tr>
              <td class="S16 M33"></td>
              <td class="S16 M32"></td>
              <td class="S16 M31"></td>
              <td class="S16 M30"></td>
              <td class="S16 M29"></td>
              <td class="S16 M28"></td>
              <td class="S16 M27"></td>
              <td class="S16 M26"></td>
              <td class="S16 M25"></td>
              <td class="S16 M24"></td>
              <td class="S16 M23"></td>
              <td class="S16 M22"></td>
              <td class="S16 M21"></td>
              <td class="S16 M20"></td>
              <td class="S16 M19"></td>
              <td class="S16 M18"></td>
              <td class="S16 M17"></td>
              <td class="S16 M16"></td>
              <td class="S16 M15"></td>
              <td class="S16 M14"></td>
              <td class="S16 M13"></td>
              <td class="S16 M12"></td>
            </tr>
            <tr>
              <td class="S15 M33"></td>
              <td class="S15 M32"></td>
              <td class="S15 M31"></td>
              <td class="S15 M30"></td>
              <td class="S15 M29"></td>
              <td class="S15 M28"></td>
              <td class="S15 M27"></td>
              <td class="S15 M26"></td>
              <td class="S15 M25"></td>
              <td class="S15 M24"></td>
              <td class="S15 M23"></td>
              <td class="S15 M22"></td>
              <td class="S15 M21"></td>
              <td class="S15 M20"></td>
              <td class="S15 M19"></td>
              <td class="S15 M18"></td>
              <td class="S15 M17"></td>
              <td class="S15 M16"></td>
              <td class="S15 M15"></td>
              <td class="S15 M14"></td>
              <td class="S15 M13"></td>
              <td class="S15 M12"></td>
            </tr>
            <tr>
              <td class="S14 M33"></td>
              <td class="S14 M32"></td>
              <td class="S14 M31"></td>
              <td class="S14 M30"></td>
              <td class="S14 M29"></td>
              <td class="S14 M28"></td>
              <td class="S14 M27"></td>
              <td class="S14 M26"></td>
              <td class="S14 M25"></td>
              <td class="S14 M24"></td>
              <td class="S14 M23"></td>
              <td class="S14 M22"></td>
              <td class="S14 M21"></td>
              <td class="S14 M20"></td>
              <td class="S14 M19"></td>
              <td class="S14 M18"></td>
              <td class="S14 M17"></td>
              <td class="S14 M16"></td>
              <td class="S14 M15"></td>
              <td class="S14 M14"></td>
              <td class="S14 M13"></td>
              <td class="S14 M12"></td>
            </tr>
            <tr>
              <td class="S13 M33"></td>
              <td class="S13 M32"></td>
              <td class="S13 M31"></td>
              <td class="S13 M30"></td>
              <td class="S13 M29"></td>
              <td class="S13 M28"></td>
              <td class="S13 M27"></td>
              <td class="S13 M26"></td>
              <td class="S13 M25"></td>
              <td class="S13 M24"></td>
              <td class="S13 M23"></td>
              <td class="S13 M22"></td>
              <td class="S13 M21"></td>
              <td class="S13 M20"></td>
              <td class="S13 M19"></td>
              <td class="S13 M18"></td>
              <td class="S13 M17"></td>
              <td class="S13 M16"></td>
              <td class="S13 M15"></td>
              <td class="S13 M14"></td>
              <td class="S13 M13"></td>
              <td class="S13 M12"></td>
            </tr>
            <tr>
              <td class="S12 M33"></td>
              <td class="S12 M32"></td>
              <td class="S12 M31"></td>
              <td class="S12 M30"></td>
              <td class="S12 M29"></td>
              <td class="S12 M28"></td>
              <td class="S12 M27"></td>
              <td class="S12 M26"></td>
              <td class="S12 M25"></td>
              <td class="S12 M24"></td>
              <td class="S12 M23"></td>
              <td class="S12 M22"></td>
              <td class="S12 M21"></td>
              <td class="S12 M20"></td>
              <td class="S12 M19"></td>
              <td class="S12 M18"></td>
              <td class="S12 M17"></td>
              <td class="S12 M16"></td>
              <td class="S12 M15"></td>
              <td class="S12 M14"></td>
              <td class="S12 M13"></td>
              <td class="S12 M12"></td>
            </tr>
            <tr>
              <td class="S11 M33"></td>
              <td class="S11 M32"></td>
              <td class="S11 M31"></td>
              <td class="S11 M30"></td>
              <td class="S11 M29"></td>
              <td class="S11 M28"></td>
              <td class="S11 M27"></td>
              <td class="S11 M26"></td>
              <td class="S11 M25"></td>
              <td class="S11 M24"></td>
              <td class="S11 M23"></td>
              <td class="S11 M22"></td>
              <td class="S11 M21"></td>
              <td class="S11 M20"></td>
              <td class="S11 M19"></td>
              <td class="S11 M18"></td>
              <td class="S11 M17"></td>
              <td class="S11 M16"></td>
              <td class="S11 M15"></td>
              <td class="S11 M14"></td>
              <td class="S11 M13"></td>
              <td class="S11 M12"></td>
            </tr>
            <tr>
              <td class="S10 M33"></td>
              <td class="S10 M32"></td>
              <td class="S10 M31"></td>
              <td class="S10 M30"></td>
              <td class="S10 M29"></td>
              <td class="S10 M28"></td>
              <td class="S10 M27"></td>
              <td class="S10 M26"></td>
              <td class="S10 M25"></td>
              <td class="S10 M24"></td>
              <td class="S10 M23"></td>
              <td class="S10 M22"></td>
              <td class="S10 M21"></td>
              <td class="S10 M20"></td>
              <td class="S10 M19"></td>
              <td class="S10 M18"></td>
              <td class="S10 M17"></td>
              <td class="S10 M16"></td>
              <td class="S10 M15"></td>
              <td class="S10 M14"></td>
              <td class="S10 M13"></td>
              <td class="S10 M12"></td>
            </tr>
            <tr>
              <td class="S09 M33"></td>
              <td class="S09 M32"></td>
              <td class="S09 M31"></td>
              <td class="S09 M30"></td>
              <td class="S09 M29"></td>
              <td class="S09 M28"></td>
              <td class="S09 M27"></td>
              <td class="S09 M26"></td>
              <td class="S09 M25"></td>
              <td class="S09 M24"></td>
              <td class="S09 M23"></td>
              <td class="S09 M22"></td>
              <td class="S09 M21"></td>
              <td class="S09 M20"></td>
              <td class="S09 M19"></td>
              <td class="S09 M18"></td>
              <td class="S09 M17"></td>
              <td class="S09 M16"></td>
              <td class="S09 M15"></td>
              <td class="S09 M14"></td>
              <td class="S09 M13"></td>
              <td class="S09 M12"></td>
            </tr>
            <tr>
              <td class="S08 M33"></td>
              <td class="S08 M32"></td>
              <td class="S08 M31"></td>
              <td class="S08 M30"></td>
              <td class="S08 M29"></td>
              <td class="S08 M28"></td>
              <td class="S08 M27"></td>
              <td class="S08 M26"></td>
              <td class="S08 M25"></td>
              <td class="S08 M24"></td>
              <td class="S08 M23"></td>
              <td class="S08 M22"></td>
              <td class="S08 M21"></td>
              <td class="S08 M20"></td>
              <td class="S08 M19"></td>
              <td class="S08 M18"></td>
              <td class="S08 M17"></td>
              <td class="S08 M16"></td>
              <td class="S08 M15"></td>
              <td class="S08 M14"></td>
              <td class="S08 M13"></td>
              <td class="S08 M12"></td>
            </tr>
            <tr>
              <td class="S07 M33"></td>
              <td class="S07 M32"></td>
              <td class="S07 M31"></td>
              <td class="S07 M30"></td>
              <td class="S07 M29"></td>
              <td class="S07 M28"></td>
              <td class="S07 M27"></td>
              <td class="S07 M26"></td>
              <td class="S07 M25"></td>
              <td class="S07 M24"></td>
              <td class="S07 M23"></td>
              <td class="S07 M22"></td>
              <td class="S07 M21"></td>
              <td class="S07 M20"></td>
              <td class="S07 M19"></td>
              <td class="S07 M18"></td>
              <td class="S07 M17"></td>
              <td class="S07 M16"></td>
              <td class="S07 M15"></td>
              <td class="S07 M14"></td>
              <td class="S07 M13"></td>
              <td class="S07 M12"></td>
            </tr>
            <tr>
              <td class="S06 M33"></td>
              <td class="S06 M32"></td>
              <td class="S06 M31"></td>
              <td class="S06 M30"></td>
              <td class="S06 M29"></td>
              <td class="S06 M28"></td>
              <td class="S06 M27"></td>
              <td class="S06 M26"></td>
              <td class="S06 M25"></td>
              <td class="S06 M24"></td>
              <td class="S06 M23"></td>
              <td class="S06 M22"></td>
              <td class="S06 M21"></td>
              <td class="S06 M20"></td>
              <td class="S06 M19"></td>
              <td class="S06 M18"></td>
              <td class="S06 M17"></td>
              <td class="S06 M16"></td>
              <td class="S06 M15"></td>
              <td class="S06 M14"></td>
              <td class="S06 M13"></td>
              <td class="S06 M12"></td>
            </tr>
            <tr>
              <td class="S05 M33"></td>
              <td class="S05 M32"></td>
              <td class="S05 M31"></td>
              <td class="S05 M30"></td>
              <td class="S05 M29"></td>
              <td class="S05 M28"></td>
              <td class="S05 M27"></td>
              <td class="S05 M26"></td>
              <td class="S05 M25"></td>
              <td class="S05 M24"></td>
              <td class="S05 M23"></td>
              <td class="S05 M22"></td>
              <td class="S05 M21"></td>
              <td class="S05 M20"></td>
              <td class="S05 M19"></td>
              <td class="S05 M18"></td>
              <td class="S05 M17"></td>
              <td class="S05 M16"></td>
              <td class="S05 M15"></td>
              <td class="S05 M14"></td>
              <td class="S05 M13"></td>
              <td class="S05 M12"></td>
            </tr>
            <tr>
              <td class="S04 M33"></td>
              <td class="S04 M32"></td>
              <td class="S04 M31"></td>
              <td class="S04 M30"></td>
              <td class="S04 M29"></td>
              <td class="S04 M28"></td>
              <td class="S04 M27"></td>
              <td class="S04 M26"></td>
              <td class="S04 M25"></td>
              <td class="S04 M24"></td>
              <td class="S04 M23"></td>
              <td class="S04 M22"></td>
              <td class="S04 M21"></td>
              <td class="S04 M20"></td>
              <td class="S04 M19"></td>
              <td class="S04 M18"></td>
              <td class="S04 M17"></td>
              <td class="S04 M16"></td>
              <td class="S04 M15"></td>
              <td class="S04 M14"></td>
              <td class="S04 M13"></td>
              <td class="S04 M12"></td>
            </tr>
            <tr>
              <td class="S03 M33"></td>
              <td class="S03 M32"></td>
              <td class="S03 M31"></td>
              <td class="S03 M30"></td>
              <td class="S03 M29"></td>
              <td class="S03 M28"></td>
              <td class="S03 M27"></td>
              <td class="S03 M26"></td>
              <td class="S03 M25"></td>
              <td class="S03 M24"></td>
              <td class="S03 M23"></td>
              <td class="S03 M22"></td>
              <td class="S03 M21"></td>
              <td class="S03 M20"></td>
              <td class="S03 M19"></td>
              <td class="S03 M18"></td>
              <td class="S03 M17"></td>
              <td class="S03 M16"></td>
              <td class="S03 M15"></td>
              <td class="S03 M14"></td>
              <td class="S03 M13"></td>
              <td class="S03 M12"></td>
            </tr>
            <tr>
              <td class="S02 M33"></td>
              <td class="S02 M32"></td>
              <td class="S02 M31"></td>
              <td class="S02 M30"></td>
              <td class="S02 M29"></td>
              <td class="S02 M28"></td>
              <td class="S02 M27"></td>
              <td class="S02 M26"></td>
              <td class="S02 M25"></td>
              <td class="S02 M24"></td>
              <td class="S02 M23"></td>
              <td class="S02 M22"></td>
              <td class="S02 M21"></td>
              <td class="S02 M20"></td>
              <td class="S02 M19"></td>
              <td class="S02 M18"></td>
              <td class="S02 M17"></td>
              <td class="S02 M16"></td>
              <td class="S02 M15"></td>
              <td class="S02 M14"></td>
              <td class="S02 M13"></td>
              <td class="S02 M12"></td>
            </tr>
            <tr>
              <td class="S01 M33"></td>
              <td class="S01 M32"></td>
              <td class="S01 M31"></td>
              <td class="S01 M30"></td>
              <td class="S01 M29"></td>
              <td class="S01 M28"></td>
              <td class="S01 M27"></td>
              <td class="S01 M26"></td>
              <td class="S01 M25"></td>
              <td class="S01 M24"></td>
              <td class="S01 M23"></td>
              <td class="S01 M22"></td>
              <td class="S01 M21"></td>
              <td class="S01 M20"></td>
              <td class="S01 M19"></td>
              <td class="S01 M18"></td>
              <td class="S01 M17"></td>
              <td class="S01 M16"></td>
              <td class="S01 M15"></td>
              <td class="S01 M14"></td>
              <td class="S01 M13"></td>
              <td class="S01 M12"></td>
            </tr>
            <tr>
              <td class="S00 M33"></td>
              <td class="S00 M32"></td>
              <td class="S00 M31"></td>
              <td class="S00 M30"></td>
              <td class="S00 M29"></td>
              <td class="S00 M28"></td>
              <td class="S00 M27"></td>
              <td class="S00 M26"></td>
              <td class="S00 M25"></td>
              <td class="S00 M24"></td>
              <td class="S00 M23"></td>
              <td class="S00 M22"></td>
              <td class="S00 M21"></td>
              <td class="S00 M20"></td>
              <td class="S00 M19"></td>
              <td class="S00 M18"></td>
              <td class="S00 M17"></td>
              <td class="S00 M16"></td>
              <td class="S00 M15"></td>
              <td class="S00 M14"></td>
              <td class="S00 M13"></td>
              <td class="S00 M12"></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>33</td>
              <td>32</td>
              <td>31</td>
              <td>30</td>
              <td>29</td>
              <td>28</td>
              <td>27</td>
              <td>26</td>
              <td>25</td>
              <td>24</td>
              <td>23</td>
              <td>22</td>
              <td>21</td>
              <td>20</td>
              <td>19</td>
              <td>18</td>
              <td>17</td>
              <td>16</td>
              <td>15</td>
              <td>14</td>
              <td>13</td>
              <td>12</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <h4 class="text-center">Tampak Belakang</h4>
    </div>
  </div>

</div>

<script>
  $(document).ready(function() {
    // TABLE GARUDA
    axios.get("{{ url('api/v1/dashboard/index-garuda') }}", config)
      .then(res => {
        const datas = res.data.data

        datas.map(data => {
          const row = $(`.${data.module}.${data.segment}`)

          if (data.completeness) {
            row.css("background-color", data.barcode_color)
          }
        })
      })
      .catch(err => {
        console.log(err)
      })
  })

</script>
